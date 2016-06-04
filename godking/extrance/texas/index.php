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


$ret = Model_Cache::redisMinfo(0)->get('b');
$table = Model_Table::mclient();
// $query = "INSERT INTO {$table} SET client='aa'";
// $flag = Model_Db::dbMain()->query($query);
// $flag2 = Model_Db::dbMain()->insertData($table, array('client'=>'bb'), true);
$flag3 = Model_Db::dbMain()->updateData($table, array('client'=>'update to new'), array('mid>1'));
$dump = compact('ret', 'table', 'quert', 'flag', 'flag3');
Common_Log::dump($dump);



