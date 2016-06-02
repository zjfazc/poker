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
 * @date: 2016年6月1日
 * @version: v1.0.0
 */
 defined( 'IN_GAME') or die( 'Comes Error!');
 
 class Model_Db{
 	static public  $_gDb = array();
 	
 	/**
 	 * 
 	 * @return Lib_Mysql
 	 */
 	static public function dbMain(){
 		$name = __FUNCTION__;
 		if(!isset(self::$_gDb[$name]) || !is_object(self::$_gDb[$name])){
 			self::$_gDb[$name] = new Lib_Mysql(Common_Gobal::$_gGameConfig[$name]);
 		}
 		return self::$_gDb[$name];
 	}
 }