<?php
return array(
    //'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'UncleDa',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'gejgej',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'Da_',    // 数据库表前缀
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_FIELDS_CACHE'       => false,
    'TMPL_PARSE_STRING'  =>array(
        '__ACSS__' => '/Public/Admin/styles', // 更改默认的__PUBLIC__ 替换规则
        '__AIMG__' => '/Public/Admin/images', // 更改默认的__PUBLIC__ 替换规则
        '__JS__' => '/Public/Admin/js', // 增加新的JS类库路径替换规则
        '__HCSS__' => '/Public/Home/styles', // 更改默认的__PUBLIC__ 替换规则
        '__HIMG__' => '/Public/Home/images', // 更改默认的__PUBLIC__ 替换规则
        '__HJS__' => '/Public/Home/js', // 增加新的JS类库路径替换规则
        '__UPLOAD__' => '/Uploads', // 增加新的上传路径替换规则
    ),
    'SHOW_PAGE_TRACE'		=> False,

    'SESSION_AUTO_START'    => true,
    'LOAD_EXT_CONFIG'=>'menus,language',//加载自己添加的配置文件
    //'LOAD_EXT_FILE'			=> 'user'

    'DEFAULT_AJAX_RETURN'   =>  'JSON',  // 默认AJAX 数据返回格式,可选JSON XML ...
//默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => THINK_PATH . 'Tpl/MyJump.html',
//默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => THINK_PATH . 'Tpl/MyJump.html',
);