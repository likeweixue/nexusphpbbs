<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 0);
define('IN_NEXUS', true);
define('NEXUS_START', microtime(true));
require ROOT_PATH . 'include/globalfunctions.php';
require ROOT_PATH . 'include/functions.php';
require ROOT_PATH . 'nexus/autoload.php';
require ROOT_PATH . 'nexus/Database/helpers.php';
require ROOT_PATH . 'include/constants.php';
if (file_exists(ROOT_PATH . '.env')) {
    require ROOT_PATH . 'classes/class_cache_file.php';
    $Cache = new class_cache_file();
}
define('WITH_LARAVEL', false);
\Nexus\Nexus::boot();
$hook = new \Nexus\Plugin\Hook();
$plugin = new \Nexus\Plugin\Plugin();
