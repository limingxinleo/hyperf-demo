#!/usr/bin/env php
<?php
define('WEBPATH', getenv('PWD'));
require_once __DIR__ . '/vendor/autoload.php';

Swoole\Loader::vendorInit();
Swoole::getInstance()->runConsole();
