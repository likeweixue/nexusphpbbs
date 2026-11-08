<?php

namespace Nexus\Database;


class NexusDB
{
    private $driver;

    private static $instance;

    private $isConnected = false;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    const ELOQUENT_CONNECTION_NAME = 'mysql';

    public function setDriver(DBInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }
        $instance = new self;
        $driver = self::makeDriver();
        $instance->setDriver($driver);
        return self::$instance = $instance;
    }

    public function connect($host, $username, $password, $database, $port)
    {
        $result = $this->driver->connect($host, $username, $password, $database, $port);
        if (!$result) {
            throw new DatabaseException(sprintf('[%s]: %s', $this->errno(), $this->error()));
        }
        $this->isConnected = true;
        return true;
    }

    public function autoConnect()
    {
        if ($this->isConnected()) {
            return null;
        }
        $config = nexus_config('nexus.mysql');
        return $this->connect($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
    }

    public function query(string $sql)
    {
        try {
            $this->autoConnect();
            return $this->driver->query($sql);
        } catch (\Exception $e) {
            do_log(sprintf("%s [%s] %s", $e->getMessage(), $sql, $e->getTraceAsString()));
            throw new DatabaseException($e->getMessage(), $sql);
        }

    }

    public function error()
    {
        return $this->driver->error();
    }

    public function errno()
    {
        return $this->driver->errno();
    }

    public function numRows($result)
    {
        return $this->driver->numRows($result);
    }

    public function select_db($database)
    {
        return $this->driver->selectDb($database);
    }

    public function fetchAssoc($result)
    {
        return $this->driver->fetchAssoc($result);
    }

    public function fetchRow($result)
    {
        return $this->driver->fetchRow($result);
    }

    public function fetchArray($result, $type = null)
    {
        return $this->driver->fetchArray($result, $type);
    }

    public function affectedRows()
    {
        return $this->driver->affectedRows();
    }

    public function escapeString(string $string)
    {
        $this->autoConnect();
        return $this->driver->escapeString($string);
    }

    public function lastInsertId()
    {
        return $this->driver->lastInsertId();
    }

    public function freeResult($result)
    {
        return $this->driver->freeResult($result);
    }

    public function isConnected()
    {
        return $this->isConnected;
    }

    public static function insert($table, $data)
    {
        if (empty($table) || empty($data) || !is_array($data)) {
            throw new DatabaseException("require table and data(array).");
        }
        $fields = array_map(function ($value) {return "`$value`";}, array_keys($data));
        $values = array_map(function ($value) {return sqlesc($value);}, array_values($data));
        $sql = sprintf("insert into `%s` (%s) values (%s)", $table, implode(', ', $fields), implode(', ', $values));
        sql_query($sql);
        return mysql_insert_id();
    }

    public static function update($table, $data, $whereStr)
    {
        $updateArr = [];
        foreach ($data as $field => $value) {
            $updateArr[] = "`$field` = " . sqlesc($value);
        }
        $sql = sprintf("update `%s` set %s where %s", $table, implode(', ', $updateArr), $whereStr);
        sql_query($sql);
        return mysql_affected_rows();
    }

    public static function delete($table, $whereStr, $limit = null)
    {
        $sql = "delete from $table where $whereStr";
        if (!is_null($limit)) {
            $sql .= " limit $limit";
        }
        sql_query($sql);
        return mysql_affected_rows();
    }

    public static function getOne($table, $whereStr, $fields = '*')
    {
        if ($fields != '*') {
            if (is_array($fields)) {
                $fields = implode(', ', $fields);
            }
        }
        if (empty($fields)) {
            do_log("args: " . json_encode(func_get_args()));
            throw new DatabaseException("empty fields.");
        }
        $sql = "select $fields from $table where $whereStr limit 1";
        $res = sql_query($sql);
        return mysql_fetch_assoc($res);
    }

    public static function getAll($table, $whereStr, $fields = '*')
    {
        if ($fields != '*') {
            if (is_array($fields)) {
                $fields = implode(', ', $fields);
            }
        }
        if (empty($fields)) {
            do_log("args: " . json_encode(func_get_args()));
            throw new DatabaseException("empty fields.");
        }
        $sql = "select $fields from $table where $whereStr";
        return self::select($sql);
    }

    public static function select(string $sql)
    {
        $res = sql_query($sql);
        $result = [];
        while ($row = mysql_fetch_assoc($res)) {
            $result[] = $row;
        }
        return $result;
    }



    public static function table(string $table): QueryBuilder
    {
        return new QueryBuilder($table);
    }

    public static function raw($value): RawExpression
    {
        return new RawExpression((string)$value);
    }

    public static function transaction(\Closure $callback, $attempts = 1)
    {
        self::statement('start transaction');
        try {
            $result = $callback();
            self::statement('commit');
            return $result;
        } catch (\Throwable $e) {
            self::statement('rollback');
            throw $e;
        }
    }

    public static function hasTable($table): bool
    {
        return self::hasTableDb($table);
    }

    public static function hasColumn($table, $column): bool
    {
        return !empty(self::getMysqlColumnInfo($table, $column));
    }



    public static function statement($value)
    {
        return sql_query($value);
    }


    /**
     * 取缓存实例（安装、CLI 等场景 $Cache 可能尚未初始化）
     */
    protected static function cache(): ?object
    {
        global $Cache;
        if (!is_object($Cache ?? null)) {
            if (class_exists('class_cache_file')) {
                $Cache = new \class_cache_file();
            } else {
                return null;
            }
        }
        return $Cache;
    }

    public static function remember($key, $ttl, \Closure $callback)
    {
        $Cache = self::cache();
        if (!$Cache) {
            return $callback();
        }
        $result = $Cache->get_value($key);
        if ($result === false) {
            $result = $callback();
            do_log("cache miss [$key]", 'debug');
            $Cache->cache_value($key, $result, $ttl);
        } else {
            do_log("cache hit [$key]", 'debug');
        }
        return $result;
    }

    public static function cache_put($key, $value, $ttl = 3600)
    {
        $Cache = self::cache();
        if (!$Cache) {
            return null;
        }
        return $Cache->cache_value($key, $value, $ttl);
    }

    public static function cache_get($key)
    {
        $Cache = self::cache();
        return $Cache ? $Cache->get_value($key) : false;
    }

    public static function cache_del($key)
    {
        $Cache = self::cache();
        if ($Cache) {
            $Cache->delete_value($key, true);
        }
    }

    public static function cache_del_by_pattern($pattern)
    {
        $redis = self::redis();
        $it = NULL;
        do {
            // Scan for some keys
            $arr_keys = $redis->scan($it, $pattern);

            // Redis may return empty results, so protect against that
            if ($arr_keys !== FALSE) {
                foreach($arr_keys as $str_key) {
                    do_log("[SCAN_KEY] $str_key");
                    self::cache_del($str_key);
                }
            }
        } while ($it > 0);
    }

    /**
     * Return the shared cache backend.
     *
     * Historically this returned a phpredis client.  It now returns the
     * file based store which implements the same API, so all call sites keep
     * working without Redis being installed.
     *
     * @return \Nexus\Cache\FileStore
     */
    public static function redis()
    {
        return \Nexus\Cache\FileStore::instance();
    }


    public static function hasTableDb(string $table): bool
    {
        $config = nexus_config('nexus.mysql');
        $row = self::select(sprintf(
            "select count(*) as c from information_schema.tables where table_schema = %s and table_name = %s",
            sqlesc($config['database']),
            sqlesc($table)
        ));
        return intval($row[0]['c'] ?? 0) > 0;
    }

    public static function getMysqlColumnInfo($table, $column = null)
    {
        static $driver;
        $config = nexus_config('nexus.mysql');
        if (is_null($driver)) {
            $driver = self::makeDriver();
            $driver->connect($config['host'], $config['username'], $config['password'], 'information_schema', $config['port']);
        }
        $sql = sprintf(
            "select * from COLUMNS where TABLE_SCHEMA = '%s' and TABLE_NAME = '%s'",
            $config['database'], $table
        );
        if ($column !== null) {
            $sql .= " and COLUMN_NAME = '$column'";
        }
        $res = $driver->query($sql);
        if ($column !== null) {
            return $driver->fetchAssoc($res);
        }
        $results = [];
        while ($row = $driver->fetchAssoc($res)) {
            $results[$row['COLUMN_NAME']] = $row;
        }
        return $results;
    }

    public static function makeDriver(): DBInterface
    {
        if (class_exists('mysqli')) {
            return new DBMysqli();
        }
        return new DBPdo();
    }

    public static function hasIndex($table, $indexName): bool
    {
        $results = self::select("show index from $table");
        foreach ($results as $item) {
            if ($item['Key_name'] == $indexName) {
                return true;
            }
        }
        return false;
    }



}
