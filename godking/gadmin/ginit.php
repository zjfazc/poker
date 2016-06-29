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
 * @date: 2016年6月7日
 * @version: v1.0.0
 */
defined ( 'IN_GAME' ) or die ( 'Comes Error!' );

$_REQUEST ['gid'] = 1;
$_REQUEST ['lid'] = 1;
$_REQUEST ['demo'] = 'greetisgood';
include_once '../init.php';
Lib_Setup::setPath('admin', PATH_GADMIN.'model/');

$GMType = array (
		0 => '测试服',
		1 => '正式服' 
);
$GMName = isset ( $GMName ) ? $GMName : '管理后台';

///////////  加载管理文件  ///////////
$model = isset($_REQUEST['M']) ?  strtolower(trim($_REQUEST['M'])) : 'dashboard';

$gfile = PATH_GADMIN.'model'.DS. "admin.{$model}.php";
if(!file_exists($gfile)){
	die("model : {$model} doesn't exist! {$gfile}");
}
include_once $gfile;
$class = "Admin_". ucfirst($model);
$action = isset($_REQUEST['A']) ? ucfirst(strtolower(trim($_REQUEST['A']))) : 'Index' ;
$method = 'action' . $action;
if(!method_exists($class, $method)){
	die("method : {$method} doesn't exist! {$class}");
}


$normalQuery = array(
		'gid' => $_REQUEST ['gid'],
		'lid' => $_REQUEST ['lid'],
		'demo' => $_REQUEST['demo'],
		'M' => $model,
		'A' => $action,
);
$smtCfg = array (
		'gResource' => 'res' . DS,
		'GMServer' => $GMType [( int ) SERVER_ONLINE],
		'GMName' => $GMName ,
		'gadmin' => Common_Gobal::$_gGameConfig['gadminUrl'] . "?" . http_build_query($normalQuery),
);
Lib_Smarty::gadminSmarty ()->assign ( $smtCfg );

$class::Single()->$method();

 