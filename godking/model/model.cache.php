<?php
/**
 * Comment:
 * 缓存类： redis
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

 /**
  * 缓存对象类
  * @author Kume
  *
  */
 class Model_Cache{
 	static public $_gCache = array();
 	
 	/**
 	 * 
 	 * @param unknown $mid
 	 * @return Lib_Redis
 	 */
 	static public function redisMinfo($mid){
 		$name = __FUNCTION__;
 		if(!isset(self::$_gCache[$name]) || !is_object(self::$_gCache[$name])){
 			self::$_gCache[$name] = new Lib_Redis(Common_Gobal::$_gGameConfig['redisMinfo']);
 		}
 		return self::$_gCache[$name];
 	}
 	
 }