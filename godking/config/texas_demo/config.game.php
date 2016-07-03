<?php
/**
 * Comment:
 *
 * ==============================================
 * Copy right 2016-2017 
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: Kume
 * @date: 2016年5月31日
 * @version: v1.0.0
 */
 defined( 'IN_GAME') or die( 'Comes Error!');
 define('HTTP', 'http://');
 define('SERVER_ONLINE', false);
 
 $gConfig = array();
 $gConfig['gid'] = 1;	// 游戏ID
 
 /************************   数据库配置 array(数据库地址, 用户名, 密码, 数据库名)    ************************/
 $gConfig['dbMain'] = array('127.0.0.1:3306','root', '', 'texas_member');	// 主数据库
 $gConfig['dbBk'] =array('127.0.0.1:3306','root', '', 'texas_bk');	// 配置数据库
 $gConfig['dbLog'] =array('127.0.0.1:3306','root', '', 'texas_log');	// 日志数据库

 /************************   Redis配置    ************************/
 $gConfig['redisMain'] = array('127.0.0.1', '6379');	// 主Redis
 $gConfig['redisMinfo'] = array('127.0.0.1', '6379');	// 用户Redis
 $gConfig['redisMonline'] = array('127.0.0.1', '6379');	// 在线Redis
 $gConfig['redisSystem'] = array('127.0.0.1', '6379');	// 系统Redis
 
 /************************   地址配置    ************************/
 $gConfig['webUrl'] = HTTP. 'localhost:3360';
 $gConfig['cdnUrl'] = HTTP . 'localhost:3360/cdn/texas/';
 $gConfig['feedbackUrl'] =  $gConfig['webUrl'] . '/api/feedback.php';
 $gConfig['gatewayUrl'] =  $gConfig['webUrl'] . '/api/gateway.php';
 $gConfig['gadminUrl'] =  $gConfig['webUrl'] . '/gadmin/index.php';
 
 return $gConfig;