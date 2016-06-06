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

////////////////  判断用户登录方式，查看用户是否存在   ///////////////////
if(1 == LOGINID){	// 游客登录
	$mid = Model_Member::Single()->sitemidToMid(LOGINID, $sitemid);
}


////////////////  若用户不存在，创建新用户   ///////////////////


$code = Common_Errorcode::SUCCEED;
$info = array(
		'name' => 'ken',
		'gender' => 1,
		'sitemid' => date('YmdHis'),
);
$flag = Model_Member::Single()->playRegister($info);
$user  = Model_Member::Single()->getPlayerByMid($flag['mid']);

Common_Log::dump($flag);
Common_Log::dump($user);

// Common_Function::single()->sendOut($code, $data);



