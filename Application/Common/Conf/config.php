<?php
return array(
	//'配置项'=>'配置值'
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'name-pku9104038-cas-mysql.cv3h2pqohkty.rds.cn-north-1.amazonaws.com.cn', // 服务器地址
    'DB_NAME'   => 'casmanagement', // 数据库名
    'DB_USER'   => 'mysqlcasmg', // 用户名
    'DB_PWD'    => 'casmg123456', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_PARAMS'    =>    array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL)


);