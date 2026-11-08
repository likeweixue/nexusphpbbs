<?php
/**
 * 在线升级入口（0.2.x 停用）。
 *
 * 覆盖程序文件后，按发布说明执行 database/install/upgrade.sql 并清理 storage 缓存即可。
 */
$rootpath = dirname(dirname(__DIR__)) . '/';
define('ROOT_PATH', $rootpath);
require ROOT_PATH . 'include/globalfunctions.php';
require ROOT_PATH . 'nexus/autoload.php';
$update = new \Nexus\Install\Update();
$update->renderDisabledPage();
