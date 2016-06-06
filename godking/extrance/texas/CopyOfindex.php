<?php
/**
 * Comment:
 * 登录入口文件
 * ==============================================
 * Copy right 2016-2017 
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: Kume
 * @date: 2016年5月29日
 * @version: v1.0.0
 */
define('IN_GAME', 'TRUE');

require_once 'common.php';

// user
// hallip
// sesskey
// isNewer
// isFirst
// cdnUrl
// feedbackUrl
// gatewayUrl

$code = Common_Errorcode::SUCCEED;
$mid = 1;
$data = array(
		'user' => array(
				'mid' => $mid,
				'sitemid' => md5(TODAY),	
				'name' => 'tester',
				'gender' => 0,
				'icon' => '',
				'lid' => '1',
				'gid' => '1',
				'regtime' => TODAY-86400,
				'twin' => 0,
				'tlose' => 0,
				'tmaxCard' => '100',
				'tmaxWin' => 5000,
				'mltime' => time(),
		),
		'hallip' => array('172.19.228.59', '6140'),
		'sesskey' => base64_encode("{$mid}-TODAY"),
		'isNewer' => 1,
		'isFirst' => 0,
		'cdnUrl' => Common_Gobal::$_gGameConfig['cdnUrl'],
		'feedbackUrl' => Common_Gobal::$_gGameConfig['feedbackUrl'],
		'gatewayUrl' => Common_Gobal::$_gGameConfig['gatewayUrl'],
);
Common_Function::single()->sendOut($code, $data);



