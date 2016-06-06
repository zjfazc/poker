<?php
/**
 * Comment:
 * 初始化文件，系统运行时加载此文件。
 * ==============================================
 * Copy right 2016-2017 
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: Kume
 * @date: 2016年5月24日
 * @version: v1.0.0 	
 */

defined( 'IN_GAME') or die( 'Comes Error!');
mb_internal_encoding( "UTF-8");
date_default_timezone_set( 'Asia/Shanghai');

///////////////////////  定义宏  //////////////////////////////
define('DS', DIRECTORY_SEPARATOR );
define( 'PATH_ROOT', dirname( __FILE__) . DS );
define('PATH_LIB', PATH_ROOT . 'lib' . DS);
define('PATH_MODEL', PATH_ROOT . 'model' . DS);
define('PATH_COMMON', PATH_ROOT . 'common' . DS);
define('PATH_LOG', PATH_ROOT . 'log' . DS);
define('PATH_VIEW', PATH_ROOT . 'view' . DS);
define('PATH_GADMIN', PATH_ROOT . 'gadmin' . DS);

define('NOW', time());
define('TODAY', mktime(0,0,0));
define('SCRIPT_STIME', microtime(true));

///////////////////////////  初始化：加载配置; 验证登录参数  ///////////////////////////////
include_once PATH_LIB . 'lib.setup.php';
Lib_Setup::init();
// include_once PATH_LIB . 'lib.phperror.php';
Common_Gobal::initCommonConfig();
$gChannelid = isset($_REQUEST['cid']) ? Common_Function::single()->uint($_REQUEST['cid']) : 0;	// 渠道id
$gGameid = isset($_REQUEST['gid']) ? Common_Function::single()->uint($_REQUEST['gid']) : 0;	// 游戏类型id
$gLoginid = isset($_REQUEST['lid']) ? Common_Function::single()->uint($_REQUEST['lid']) : 0;	// 登录类型id
///////////  验证   ////////////
if(empty($gGameid) && !array_key_exists($gGameid, Common_Gobal::$_gCommonConfig['gids'])){
	die("wrong gameid! gameid: ".$gameid);
}
if(empty($gLoginid) && !array_key_exists($gLoginid, Common_Gobal::$_gCommonConfig['lids'])){
	die("wrong login! login: ".$gLoginid);
}

/////////////////////// 继续 定义宏  //////////////////////////////
if(isset($_REQUEST['demo']) && 'greetisgood'==$_REQUEST['demo']){
	define('PATH_CONFIG', PATH_ROOT . 'config' . DS . Common_Gobal::$_gCommonConfig['gids'][$gGameid][0] . '_demo' . DS);
}else{
	define('PATH_CONFIG', PATH_ROOT . 'config' . DS . Common_Gobal::$_gCommonConfig['gids'][$gGameid][0] . DS);
}
define('GAMEID', $gGameid);
define('CHANNEL', $gChannelid);
define('LOGINID', $gLoginid);

/////////////////////// 继续加载配置  //////////////////////////////
Common_Gobal::initGameConfig();	// 加载游戏对应配置
Common_Gobal::header();
// Common_Gobal::nocache();

Common_Function::single()->magic_quote($_GET);
Common_Function::single()->magic_quote($_POST);
Common_Function::single()->magic_quote($_REQUEST);

