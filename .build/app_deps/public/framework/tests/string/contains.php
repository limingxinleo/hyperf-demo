<?php
define('DEBUG', 'on');
define('WEBPATH', realpath(__DIR__ . '/../../'));
//包含框架入口文件
require WEBPATH . '/libs/lib_config.php';

var_dump(_string("hello world")->contains('hello'));
var_dump(_string("hello world")->contains('rango'));

var_dump(_array(["hello", "world"])->contains("hello"));