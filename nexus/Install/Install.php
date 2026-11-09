<?php

namespace Nexus\Install;

use App\Models\Setting;
use App\Models\TrackerUrl;
use App\Models\User;
use App\Repositories\SearchBoxRepository;
use App\Repositories\UserRepository;
use Nexus\Support\Str;
use Nexus\Database\NexusDB;

class Install
{
    protected $currentStep;

    protected $minimumPhpVersion = '8.2.0';

    protected $progressKeyPrefix = '__step';

    protected $steps = ['Env check', 'Create .env', 'Create tables', 'Import data', 'Create administrator'];

    protected $initializeTables = [
        'adminpanel', 'agent_allowed_exception', 'agent_allowed_family', 'allowedemails', 'audiocodecs', 'bannedemails', 'categories',
        'caticons', 'codecs', 'countries', 'downloadspeed', 'faq', 'isp', 'language', 'media', 'modpanel', 'processings', 'rules', 'schools',
        'searchbox', 'secondicons', 'sources', 'standards', 'stylesheets', 'sysoppanel', 'teams', 'torrents_state', 'uploadspeed',
    ];

    protected $envNames = [
        'TIMEZONE',
        'DB_HOST', 'DB_PORT', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE',
        'UID_STARTS',
    ];

    protected array $requiredExtensions = [
        'ctype', 'curl', 'fileinfo', 'json', 'mbstring', 'openssl', 'pdo_mysql', 'tokenizer', 'xml',
        'mysqli', 'bcmath', 'gd', 'gmp', 'zip', 'intl', 'sqlite3', 'pdo_sqlite'
    ];

    protected array $conflictExtensions = [
        'mysql',
    ];
    protected array $optionalExtensions = [
//        ['name' => 'swoole', 'desc' => "If use swoole for Octane, make sure 'current' shows 1"],
    ];
    protected array $requiredFunctions = [
        'symlink', 'putenv', 'proc_open', 'proc_get_status', 'exec',
    ];

    const INSTALL_LOCK_FILE = 'dont_delete_install.lock';

    protected string $lockFile = self::INSTALL_LOCK_FILE;

    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
        if (!$this->runningInConsole()) {
            $this->checkLock();
        }
        $this->currentStep = min(intval($_REQUEST['step'] ?? 1) ?: 1, count($this->steps) + 1);
    }

    public function listShouldInitializeTables()
    {
        return $this->initializeTables;
    }

    public function currentStep()
    {
        return $this->currentStep;
    }

    public function runningInConsole(): bool
    {
        return php_sapi_name() == 'cli';
    }

    public function canAccessStep($step)
    {
        for ($i = 1; $i < $step; $i++) {
            $progressKey = $this->getProgressKey($i);
            if (!isset($_SESSION[$progressKey])) {
                $this->doLog("check step: $i, session doesn't have: " . json_encode($_SESSION));
                return false;
            }
        }
        $this->doLog("check step: $step, can access" );
        return true;
    }

    public function doneStep($step)
    {
        $progressKey = $this->getProgressKey($step);
        $this->doLog("doneStep: $step, $progressKey = 1");
        $_SESSION[$progressKey] = 1;
    }

    private function getProgressKey($step)
    {
        return $this->progressKeyPrefix . $step;
    }

    public function getLogFile()
    {
        return sprintf('%s/nexus-install-%s.log', sys_get_temp_dir(), date('Ymd'));
    }

    public function getInsallDirectory()
    {
        return ROOT_PATH . 'public/install';
    }

    public function doLog($log)
    {
        $log = sprintf('[%s] [%s] %s%s', date('Y-m-d H:i:s'), $this->currentStep, $log, PHP_EOL);
        file_put_contents($this->getLogFile(), $log, FILE_APPEND);
    }

    public function listAllTableCreate($sqlFile = '')
    {
        if (empty($sqlFile)) {
            $sqlFile = ROOT_PATH . '_db/dbstructure_v1.6.sql';
        }
        $pattern = '/CREATE TABLE `(.*)`.*;/isU';
        $string = file_get_contents($sqlFile);
        if ($string === false) {
            throw new \RuntimeException("sql file: $sqlFile can not read, make sure it exits and can be read.");
        }
        $count = preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);
        if ($count == 0) {
            return [];
        }
        return array_column($matches, 0, 1);
    }

    public function listAllTableCreateFromMigrations()
    {
        $tables = [];
        foreach (glob(ROOT_PATH . "database/migrations/*.php") as $path) {
            $filename = basename($path);
            $count = preg_match('/create_(.*)_table.php/', $filename, $matches);
            if ($count) {
                $tableName = $matches[1];
                //Special treatment
                $tableName = str_replace("seedbox_records", "seed_box_records", $tableName);
                $tables[$tableName] = "database/migrations/$filename";
            }
        }
        return $tables;
    }

    public function listExistsTable()
    {
        $sql = 'show tables';
        $res = sql_query($sql);
        $data = [];
        while ($row = mysql_fetch_row($res)) {
            $data[] = $row[0];
        }
        return $data;
    }

    public function listRequirementTableRows()
    {
        $gdInfo = function_exists('gd_info') ? gd_info() : [];
        $tableRows = [];
        $phpVersionRequire = '>= ' . $this->minimumPhpVersion;
        $tableRows[] = [
            'label' => 'PHP version',
            'required' => $phpVersionRequire,
            'current' => PHP_VERSION,
            'result' => $this->yesOrNo(version_compare(PHP_VERSION, $this->minimumPhpVersion, '>=')),
        ];

        $disabledFunctions = [];
        foreach ($this->requiredFunctions as $fn) {
            if (str_starts_with($fn, "pcntl_") && function_exists('exec')) {
                $output = [];
                $command = "php -r 'var_export(function_exists(\"$fn\"));'";
                $result = exec($command, $output, $result_code);
                $lastFourChars = substr(trim($output[0]), -4);
                $exists = $lastFourChars == 'true';
                if (!$exists) {
                    $disabledFunctions[] = $fn;
                }
            } elseif (!function_exists($fn)) {
                $disabledFunctions[] = $fn;
            }
        }

        $tableRows[] = [
            'label' => 'Required functions',
            'required' => 'true',
            'current' => empty($disabledFunctions) ? '1' : "These functions are Disabled: " . implode(',', $disabledFunctions),
            'result' => $this->yesOrNo(empty($disabledFunctions)),
        ];

        foreach ($this->conflictExtensions as $extension) {
            $loaded = extension_loaded($extension);
            $tableRows[] = [
                'label' => "PHP extension $extension",
                'required' => 'disabled',
                'current' => (int)$loaded,
                'result' => $loaded ? 'NO' : 'YES',
            ];
        }

        foreach ($this->requiredExtensions as $extension) {
            if ($extension == 'pcntl' && function_exists('exec')) {
                $output = [];
                $result = exec("php -m", $output, $result_code);
                $outputString = implode("\n", $output);
                $loadedStr = $outputString;
                $loadedArr = preg_split("/[\r\n]+/", $loadedStr);
                $loaded = in_array($extension, $loadedArr);
            } else {
                $loaded = extension_loaded($extension);
            }
            $tableRows[] = [
                'label' => "PHP extension $extension",
                'required' => 'enabled',
                'current' => (int)$loaded,
                'result' => $this->yesOrNo($loaded),
            ];
        }

        $tableRows[] = [
            'label' => 'PHP extension gd JPEG Support',
            'required' => 'true',
            'current' => $gdInfo['JPEG Support'] ?? '',
            'result' => $this->yesOrNo($gdInfo['JPEG Support'] ?? ''),
        ];
        $tableRows[] = [
            'label' => 'PHP extension gd PNG Support',
            'required' => 'true',
            'current' => $gdInfo['PNG Support'] ?? '',
            'result' => $this->yesOrNo($gdInfo['PNG Support'] ?? ''),
        ];
        $tableRows[] = [
            'label' => 'PHP extension gd GIF Read Support',
            'required' => 'true',
            'current' => $gdInfo['GIF Read Support'] ?? '',
            'result' => $this->yesOrNo($gdInfo['GIF Read Support'] ?? ''),
        ];

        foreach ($this->optionalExtensions as $extension) {
            $tableRows[] = [
                'label' => "PHP extension " . $extension['name'],
                'required' => 'enabled',
                'current' => (int)extension_loaded($extension['name']),
                'result' => $extension['desc'],
            ];
        }

        $fails = array_filter($tableRows, function ($value) use ($phpVersionRequire) {
            return in_array($value['required'], ['true', 'enabled', $phpVersionRequire]) && $value['result'] == 'NO';
        });
        $pass = empty($fails);
        return [
            'table_rows' => $tableRows,
            'fails' => $fails,
            'pass' => $pass,
        ];
    }

    public function listSettingTableRows()
    {
        $defaultSettingsFile = __DIR__ . '/settings.default.php';
        $originalConfigFile = ROOT_PATH . 'config/allconfig.php';
        if (!file_exists($defaultSettingsFile)) {
            throw new \RuntimeException("default setting file: $defaultSettingsFile not exists.");
        }
        if (!file_exists($originalConfigFile)) {
            throw new \RuntimeException("original setting file: $originalConfigFile not exists.");
        }
        $tableRows = [
            [
                'label' => basename($defaultSettingsFile),
                'required' => 'exists && readable',
                'current' => $defaultSettingsFile,
                'result' =>  $this->yesOrNo(file_exists($defaultSettingsFile) && is_readable($defaultSettingsFile)),
            ],
            [
                'label' => basename($originalConfigFile),
                'required' => 'exists && readable',
                'current' => $originalConfigFile,
                'result' =>  $this->yesOrNo(file_exists($originalConfigFile) && is_readable($originalConfigFile)),
            ],
        ];
        $requireDirs = [
            'main' => ['bitbucket', ],
            'attachment' => ['savedirectory', ],
        ];
        $symbolicLinks = [];
        require $originalConfigFile;
        $settings = require $defaultSettingsFile;
        $settingsFromDb = [];
        if (NexusDB::hasTable('settings') && get_row_count('settings') > 0) {
            $settingsFromDb = Setting::getFromDb();
        }
        $this->doLog("settings form db: " . json_encode($settingsFromDb));
        foreach ($settings as $prefix => &$group) {
            $prefixUpperCase = strtoupper($prefix);
            $oldGroupValues = $$prefixUpperCase ?? null;
            foreach ($group as $key => &$value) {
                //merge original config or db config to default setting, exclude code part
                if ($prefix != 'code') {
                    if (isset($settingsFromDb[$prefix][$key])) {
                        $this->doLog(sprintf(
                            "$prefix.$key, db exists, change from: %s => %s",
                            is_scalar($value) ? $value : json_encode($value),
                            is_scalar($settingsFromDb[$prefix][$key]) ? $settingsFromDb[$prefix][$key] : json_encode($settingsFromDb[$prefix][$key]))
                        );
                        $value = $settingsFromDb[$prefix][$key];
                    } elseif (isset($oldGroupValues) && isset($oldGroupValues[$key])) {
                        $this->doLog(sprintf(
                            "$prefix.$key, original config file exists, change from: %s => %s",
                            is_scalar($value) ? $value : json_encode($value),
                            is_scalar($oldGroupValues[$key]) ? $oldGroupValues[$key] : json_encode($oldGroupValues[$key]))
                        );
                        $value = $oldGroupValues[$key];
                    }
                }
                if ($prefix == 'basic' && Str::startsWith($value, 'localhost')) {
                    $value = '';
                }
                if (isset($requireDirs[$prefix]) && in_array($key, $requireDirs[$prefix])) {
                    $dir = getFullDirectory($value);
                    $tableRows[] = [
                        'label' => "{$prefix}.{$key}",
                        'required' => 'exists && readable',
                        'current' => $dir,
                        'result' => $this->yesOrNo(is_dir($dir) && is_readable($dir)),
                    ];
                    $symbolicLinks[] = $dir;
                }
            }
        }
        $fails = array_filter($tableRows, function ($value) {return $value['required'] == 'true' && $value['result'] == 'NO';});
        $pass = empty($fails);
        return [
            'table_rows' => $tableRows,
            'symbolic_links' => $symbolicLinks,
            'settings' => $settings,
            'fails' => $fails,
            'pass' => $pass,
        ];
    }

    public function nextStep()
    {
        $this->doneStep($this->currentStep);
        $this->gotoStep($this->currentStep + 1);
    }

    public function gotoStep($step)
    {
        if ($this->runningInConsole()) {
            $this->currentStep = $step;
        } else {
            nexus_redirect(getBaseUrl() . "?step=$step");
            die(0);
        }

    }

    public function maxStep()
    {
        return count($this->steps);
    }

    public function yesOrNo($condition) {
        if ($condition) {
            return 'YES';
        }
        return 'NO';
    }

    public function renderTable($header, $data)
    {
        $table = '<div class="table w-full text-left">';
        $table .= '<div class="table-row-group">';
        $table .= '<div class="table-row">';
        foreach ($header as $text) {
            $table .= '<div class="table-cell bg-gray-400 text-gray-700 px-4 py-2">' . $text . '</div>';
        }
        $table .= '</div>';
        foreach ($data as $value) {
            $table .= '<div class="table-row">';
            foreach ($header as $name => $text) {
                $color = 'gray';
                if ($name == 'result' && in_array($value[$name], ['YES', 'NO'])) {
                    $color = $value[$name] == 'YES' ? 'green' : 'red';
                }
                $table .= '<div class="table-cell bg-gray-200 text-' . $color . '-700 px-4 py-2 text-sm">' . $value[$name] . '</div>';
            }
            $table .= '</div>';
        }
        $table .= '</div>';
        $table .= '</div>';

        return $table;

    }

    public function renderForm($formControls, $formWidth = '1/2', $labelWidth = '1/3', $valueWidth = '2/3')
    {
        $form = '<div class="inline-block w-' . $formWidth . '">';
        foreach ($formControls as $value) {
            $form .= '<div class="flex mt-2">';
            $form .= sprintf('<div class="w-%s flex justify-end items-center pr-10"><span>%s</span></div>', $labelWidth, $value['label']);
            $form .= sprintf('<div class="w-%s flex justify-start items-center pr-10">', $valueWidth);
            if (isset($value['type']) && $value['type'] == 'select') {
                $form .= sprintf('<select class="border py-2 px-3 text-grey-darkest w-full" name="%s"/>', $value['name']);
                foreach ($value['options'] as $option) {
                    $selected = '';
                    if ($option == $value['value']) {
                        $selected = ' selected';
                    }
                    $form .= sprintf('<option value="%s"%s>%s</option>', $option, $selected, $option);
                }
                $form .= '</select>';
            } else {
                $form .= sprintf(
                    '<input class="border py-2 px-3 text-grey-darkest w-full" type="text" name="%s" value="%s" />',
                    $value['name'], $value['value'] ?? ''
                );
            }
            $form .= '</div>';
            $form .= '</div>';
        }
        $form .= '</div>';
        return $form;
    }

    public function renderSteps()
    {
        $steps = '<div class="flex mt-10 step text-center">';
        $currentStep = $this->currentStep();
        foreach ($this->steps as $key => $value) {
            $steps .= sprintf('<div class="flex-1 %s">', $currentStep > $key + 1 ? 'text-green-500' : ($currentStep < $key + 1 ? 'text-gray-500' : ''));
            $steps .= sprintf('<div>step %s</div>', $key + 1);
            $steps .= sprintf('<div>%s</div>', $value);
            $steps .= '</div>';
        }
        $steps .= '</div>';
        return $steps;
    }

    public function listEnvFormControls()
    {
        $envExampleFile = ROOT_PATH . ".env.example";
        $envExampleData = readEnvFile($envExampleFile);
        $envFile = ROOT_PATH . '.env';
        $envData = [];
        if (file_exists($envFile) && is_readable($envFile)) {
            //already exists, read it ,and merge
            $envData = readEnvFile($envFile);
        }
        $mergeData = array_merge($envExampleData, $envData);
        $formControls = [];
        foreach ($this->envNames as $name) {
            $value = $mergeData[$name];
            if (isset($_POST[$name])) {
                $value = $_POST[$name];
            }
            $item = [
                'type' => 'text',
                'options' => [],
                'label' => $name,
                'name' => $name,
                'value' => $value
            ];
            if ($name == 'TIMEZONE') {
                $item['type'] = 'select';
                $item['options'] = $this->listTimeZone();
            }
            $formControls[] = $item;
        }

        return $formControls;
    }

    public function createAdministrator($username, $email, $password, $confirmPassword)
    {
        $class = User::CLASS_STAFF_LEADER;
        $count = get_row_count('users', 'where class = ' . $class);
        if ($count > 0) {
            throw new \InvalidArgumentException("Administrator already exists");
        }
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $confirmPassword,
            'class' => $class,
            'id' => 1,
        ];
        $user = (new UserRepository())->store($data);
        $this->doLog("[CREATE ADMINISTRATOR] " . $user->toJson());
        return $user;
    }

    public function createEnvFile($data, $scene = 'install')
    {
        $envExampleFile = ROOT_PATH . ".env.example";
        $envExampleData = readEnvFile($envExampleFile);
        $envFile = ROOT_PATH . ".env";
        $newData = [];
        if (file_exists($envFile) && is_readable($envFile)) {
            //already exists, read it ,and merge post data
            $newData = readEnvFile($envFile);
            $this->doLog("[CREATE ENV] .env exists, data: " . json_encode($newData));
        }
        $this->doLog("[CREATE ENV] newData: " . json_encode($newData));
        foreach ($envExampleData as $key => $value) {
            if (isset($data[$key])) {
                $value = trim($data[$key]);
                $this->doLog("[CREATE ENV] key: $key, new value: $value from post.");
                $newData[$key] = $value;
            } elseif (!isset($newData[$key])) {
                $this->doLog("[CREATE ENV] key: $key, new value: $value from example.");
                $newData[$key] = $value;
            }
            if ($scene == 'install' || !file_exists($envFile)) {
                if ($key == 'APP_ENV') {
                    $newData[$key] = 'production';
                }
                if ($key == 'APP_DEBUG') {
                    $newData[$key] = 'false';
                }
                if ($key == 'LOG_LEVEL') {
                    $newData[$key] = 'info';
                }
            }
        }
        // No Redis: use the file cache and run jobs in-process.
        $newData['CACHE_STORE'] = 'nexus';
        $newData['SESSION_DRIVER'] = 'file';
        $newData['QUEUE_CONNECTION'] = 'sync';
        if (empty($newData['APP_KEY'])) {
            $newData['APP_KEY'] = 'base64:' . base64_encode(random_bytes(32));
        }
        if (empty($newData['APP_URL']) || $newData['APP_URL'] == 'http://localhost' || !filter_var($newData['APP_URL'], FILTER_VALIDATE_URL)) {
            $newData['APP_URL'] = getSchemeAndHttpHost();
        }
        $this->doLog("[CREATE ENV] final newData: " . json_encode($newData));
        unset($key, $value);
        mysql_connect($newData['DB_HOST'], $newData['DB_USERNAME'], $newData['DB_PASSWORD'], $newData['DB_DATABASE'], (int)$newData['DB_PORT']);
        $content = "";
        foreach ($newData as $key => $value) {
            $content .= "{$key}={$value}\n";
        }
        $fp = @fopen($envFile, 'w');
        if ($fp === false) {
            throw new \RuntimeException("can't create env file, make sure php has permission to create file at: " . ROOT_PATH);
        }
        fwrite($fp, $content);
        fclose($fp);
        $this->doLog("[CREATE ENV] $envFile with content: $content");
        return true;
    }

    public function listShouldCreateTable()
    {
        $existsTable = $this->listExistsTable();
        $tableCreate = $this->listAllTableCreateFromMigrations();
        $shouldCreateTable = [];
        foreach ($tableCreate as $table => $sql) {
            if (in_array($table, $existsTable)) {
                continue;
            }
            $shouldCreateTable[$table] = $sql;
        }
        return $shouldCreateTable;
    }

    public function createTable(array $createTable)
    {
        foreach ($createTable as $table => $sql) {
            $this->doLog("[CREATE TABLE] $table \n $sql");
            sql_query($sql);
        }
        return true;
    }

    public function saveSettings($settings)
    {
        if (!NexusDB::hasTable('settings')) {
            $this->runMigrate('database/migrations/2021_06_08_113437_create_settings_table.php');
        }
        if (!NexusDB::hasColumn('settings', 'autoload')) {
            $this->runMigrate('database/migrations/2022_05_06_191830_add_autoload_to_settings_table.php');
        }
        foreach ($settings as $prefix => $group) {
            $this->doLog("[SAVE SETTING], prefix: $prefix, nameAndValues: " . json_encode($group));
            saveSetting($prefix, $group);
        }

    }

    public function createSymbolicLinks($symbolicLinks)
    {
        foreach ($symbolicLinks as $path) {
            $linkName = ROOT_PATH . 'public/' . basename($path);
            if (is_link($linkName) || is_file($linkName)) {
                $delResult = unlink($linkName);
                $this->doLog("path: $linkName already exits, try to delete it, delResult: " . var_export($delResult, true));
            }
            if (is_dir($linkName)) {
                $this->doLog("path: $linkName already exits, skip create symbolic link $linkName -> $path");
                continue;
            }
            $linkResult = symlink($path, $linkName);
            if ($linkResult === false) {
                throw new \RuntimeException("can not make symbolic link:  $linkName -> $path");
            }
            $this->doLog("[CREATE SYMBOLIC LINK] success make symbolic link: $linkName -> $path");
        }
        return true;
    }

    public function importInitialData($sqlFile = '')
    {
        if (empty($sqlFile)) {
            $sqlFile = ROOT_PATH . '_db/dbstructure_v1.6.sql';
        }
        $string = file_get_contents($sqlFile);
        if ($string === false) {
            throw new \RuntimeException("can't not read dbstructure file: $sqlFile");
        }
        //@todo test in php 7.3
        $pattern = "/INSERT INTO `(\w+)` VALUES \(.*\);/i";
        preg_match_all($pattern, $string, $matches, PREG_SET_ORDER);
        $this->doLog("[IMPORT DATA] matches count: " . count($matches));
        foreach ($matches as $match) {
            $table = $match[1];
            $sql = trim($match[0]);
            if (!in_array($table, $this->initializeTables)) {
                continue;
            }
            //if table not empty, skip
            $count = get_row_count($table);
            if ($count > 0) {
                $this->doLog("[IMPORT DATA] $table, not empty, skip");
                continue;
            }
            $this->doLog("[IMPORT DATA] $table, $sql");
            sql_query("truncate table $table");
            sql_query($sql);
        }
        return true;
    }

    /**
     * 无 vendor 版本：不再调用 artisan migrate，改为执行内置 SQL 建表脚本。
     *
     * @param null $path 保留参数以兼容旧调用；传入 SQL 文件路径时只执行该文件
     */
    public function runMigrate($path = null)
    {
        $files = [];
        if (!is_null($path)) {
            foreach ((array)$path as $value) {
                $full = is_file($value) ? $value : ROOT_PATH . $value;
                if (is_file($full)) {
                    $files[] = $full;
                }
            }
        }
        if (empty($files)) {
            $files = [
                ROOT_PATH . 'database/install/schema.sql',
                ROOT_PATH . 'database/install/data.sql',
            ];
        }
        foreach ($files as $file) {
            if (!is_file($file)) {
                throw new \RuntimeException("install sql not found: $file");
            }
            $this->doLog("[MIGRATE] run sql file: $file");
            $this->executeSqlFile($file);
        }
        $this->doLog("[MIGRATE] success.");
    }

    /**
     * 逐条执行 SQL 文件（支持多语句、注释与 SET 语句）
     */
    public function executeSqlFile(string $file): int
    {
        $content = file_get_contents($file);
        if ($content === false) {
            throw new \RuntimeException("can not read sql file: $file");
        }
        $statements = $this->splitSqlStatements($content);
        $count = 0;
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if ($statement === '') {
                continue;
            }
            try {
                sql_query($statement);
            } catch (\Throwable $throwable) {
                throw new \RuntimeException(sprintf('sql failed: %s, error: %s', substr($statement, 0, 200), $throwable->getMessage()));
            }
            $count++;
        }
        $this->doLog("[MIGRATE] executed $count statements from $file");
        return $count;
    }

    /**
     * 把 SQL 文件拆成单条语句（正确处理引号、反引号、注释和分号）
     */
    public function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $current = '';
        $length = strlen($sql);
        $quote = null;
        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            if ($quote !== null) {
                $current .= $char;
                if ($char === '\\' && $i + 1 < $length) {
                    $current .= $sql[++$i];
                    continue;
                }
                if ($char === $quote) {
                    $quote = null;
                }
                continue;
            }
            if ($char === "'" || $char === '"' || $char === '`') {
                $quote = $char;
                $current .= $char;
                continue;
            }
            if ($char === '-' && substr($sql, $i, 3) === '-- ' ) {
                while ($i < $length && $sql[$i] !== "\n") {
                    $i++;
                }
                continue;
            }
            if ($char === '/' && substr($sql, $i, 2) === '/*') {
                $end = strpos($sql, '*/', $i + 2);
                $i = $end === false ? $length : $end + 1;
                continue;
            }
            if ($char === ';') {
                $statements[] = $current;
                $current = '';
                continue;
            }
            $current .= $char;
        }
        if (trim($current) !== '') {
            $statements[] = $current;
        }
        return $statements;
    }

    /**
     * Find the PHP CLI binary.  PHP_BINDIR points to the directory of the
     * running PHP, which contains the CLI binary on most control panels.
     */
    public function phpBinary(): string
    {
        $configured = trim((string)nexus_env('PHP_PATH', ''));
        if ($configured !== '') {
            if (str_contains($configured, '/') && is_executable($configured)) {
                return $configured;
            }
            if (!str_contains($configured, '/')) {
                return $configured;
            }
        }
        $candidates = [
            PHP_BINDIR . '/php',
            '/usr/bin/php',
            '/usr/local/bin/php',
            '/opt/homebrew/bin/php',
        ];
        foreach ($candidates as $candidate) {
            if (is_executable($candidate)) {
                return $candidate;
            }
        }

        return 'php';
    }

    public function executeCommand($command)
    {
        executeCommand($command);
//        $this->doLog("command: $command");
//        $result = exec($command, $output, $result_code);
//        $this->doLog(sprintf('result_code: %s, result: %s', $result_code, $result));
//        $this->doLog("output: " . json_encode($output));
//        if ($result_code != 0) {
//            throw new \RuntimeException(json_encode($output));
//        }
    }

    /**
     * 导入初始数据。
     *
     * 0.2.x 不再使用 Laravel seeder：初始数据由 database/install/data.sql 提供。
     * 若第 3 步已由 schema.sql 建表而第 4 步才执行到这里，则在此导入 data.sql；
     * 若数据已经存在（schema.sql 一并导入过），则跳过。
     */
    public function runDatabaseSeeder()
    {
        if (get_row_count('settings') > 0) {
            $this->doLog("[DATABASE_SEED] settings already exists, skip: " . get_row_count('settings'));
            return true;
        }
        $dataFile = ROOT_PATH . 'database/install/data.sql';
        if (!is_file($dataFile)) {
            throw new \RuntimeException('database seed file missing: ' . $dataFile);
        }
        $this->doLog("[DATABASE_SEED] import: $dataFile");
        $this->executeSqlFile($dataFile);
        $count = get_row_count('settings');
        $this->doLog("[DATABASE_SEED] settings rows: $count");
        if ($count <= 0) {
            throw new \RuntimeException('database seed data missing, please import database/install/data.sql');
        }
        return true;
    }

    public function listTimeZone()
    {
        $results = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        $utc = array_pop($results);
        array_unshift($results, $utc);
        return $results;
    }

    public function getMysqlVersionInfo(): array
    {
        $sql = 'select version() as v';
        $result = NexusDB::select($sql);
        $version = $result[0]['v'];
        $minVersion = '5.7.8';
        $match = version_compare($version, $minVersion, '>=');
        return compact('version', 'match', 'minVersion');
    }

    public function checkLock()
    {
        $fullFilename = ROOT_PATH . $this->lockFile;
        if (file_exists($fullFilename)) {
            die("Locked! Delete .lock file first");
        }
    }

    /**
     * 是否已完成安装（存在管理员账号）
     */
    public function isInstalled(): bool
    {
        try {
            if (!NexusDB::hasTable('users') || !NexusDB::hasTable('settings')) {
                return false;
            }
            return get_row_count('users') > 0;
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    public function setLock()
    {
        $fullFilename = ROOT_PATH . $this->lockFile;
        $res = file_put_contents($fullFilename, "Lock at: " . date('Y-m-d H:i:s'));
        $this->doLog("set lock at: $fullFilename, result: " . var_export($res, true));
    }

    public function getStepName($step): string
    {
        return $this->steps[$step - 1] ?? '';
    }

    public function migrateSearchBoxModeRelated()
    {
        $this->doLog("[migrateSearchBoxModeRelated]");
        $searchBoxRep = new SearchBoxRepository();
        $searchBoxRep->migrateToModeRelated();
    }

    /**
     * 初始化，注意这里不能使用 get_tracker_schema_and_host()。里面会调用 TrackerUrl， 这本来就是要往里面插入数据
     *
     * @param string $scene install or update
     * @return void
     */
    public function initTrackerUrl(string $scene): void
    {
        if ($scene == "update") {
            $announceUrl = get_setting("security.https_announce_url");
            if (empty($announceUrl)) {
                $announceUrl = get_setting("basic.announce_url");
            }
        }
        if (empty($announceUrl)) {
            $announceUrl = sprintf(
                "%s/%s",
                trim($_SERVER['HTTP_HOST'], '/'), trim(DEFAULT_TRACKER_URI, '/')
            );
        }
        if (!str_starts_with($announceUrl, "http")) {
            $announceUrl = (isHttps() ? "https://" : "http://"). $announceUrl;
        }
        NexusDB::table('tracker_urls')->create([
            "url" => $announceUrl,
            "enabled" => 1,
            "is_default" => 1,
        ]);
        TrackerUrl::saveUrlCache();
        $this->doLog("[initTrackerUrl] $announceUrl success.");
    }

}
