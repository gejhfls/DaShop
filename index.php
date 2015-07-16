<?php

/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/3/22
 * Time: 上午10:51
 */
//tp目录
define('THINK_PATH','./ThinkPHP/');
//项目目录
define('APP_PATH','./');
//项目名字与项目文件夹同名,3.2开始不必要定义
//define('APP_NAME','tp_demo');

define('APP_DEBUG', true);
define('DB_FIELDS_CACHE',false);//每次都对数据表结构进行解析
//tp入口文件
include('./ThinkPHP/ThinkPHP.php');


