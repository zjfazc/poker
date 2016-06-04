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
 * @date: 2016年6月4日
 * @version: v1.0.0
 */
 define( 'IN_GAME' , true);
 
 $_REQUEST['gid'] = 1;
 $_REQUEST['lid'] = 1;
 $_REQUEST['demo'] = 'greetisgood';
 include_once '../init.php';
 
$smtCfg = array(
		'gResource' =>  'resource' .DS,
);
Common_Log::dump($smtCfg);
Lib_Smarty::gadminSmarty()->assign($smtCfg);
Lib_Smarty::gadminSmarty()->display('cm_header.html');
 