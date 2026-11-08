<?php
/**
 * Laravel 前端控制器占位（无 vendor 版本不再使用）。
 *
 * 论坛版所有页面都是传统 PHP 页面（public/*.php），不再经过 Laravel 路由，
 * 此文件保留只是为了兼容旧入口链接，访问时直接跳转到首页。
 */
header('Location: index.php', true, 302);
exit;
