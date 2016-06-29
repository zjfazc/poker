<?php
/**
 * Comment:
 * 后台管理公共操作类
 * ==============================================
 * Copy right 2016-2017 
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: Kume
 * @date: 2016年6月18日
 * @version: v1.0.0
 */
 
defined( 'IN_GAME') or die( 'Comes Error!');
class Admin_Common{
	private static $gConfig = array();
	private static $gObj = array ();
	/**
	 * 单例
	 *
	 * @return Admin_Common	
	*/
	static public function Single() {
		$name = __FUNCTION__;
		if (! isset ( self::$gObj [$name] )) {
			$class = __CLASS__;
			self::$gObj [$name] = new $class	();
		}
		return self::$gObj [$name];
	}
	
	/**
	 * 加载管理配置
	 * @param unknown $name
	 * @return multitype:
	 */
	public function getConfig($name){
		if(empty(self::$gConfig[$name])){
			self::$gConfig[$name] = require_once PATH_GADMIN . 'config'. DS . "gconfig.{$name}.php";
		}
		return self::$gConfig[$name];
	}
	
	
}