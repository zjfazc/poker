<?php
/**
 * Comment:
 * 验证类
 * ==============================================
 * Copy right 2016-2017 
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: Kume
 * @date: 2016年6月4日
 * @version: v1.0.0
 */
 defined( 'IN_GAME') or die( 'Comes Error!');
 class Model_Verify {
 	static private $gObj = array();
 	/**
 	 * 单例
 	 * @return Model_Verify
 	 */
 	static public function Single(){
 		$name = __FUNCTION__;
 		if(!isset(self::$gObj[$name])){
 			$class = __CLASS__;
 			self::$gObj[$name] = new $class();
 		}
 		return self::$gObj[$name];
 	}
 	
 	/**
 	 * 验证登录
 	 * @return boolean
 	 */
 	public function checkSign($sign){
 		if(empty($sign)){
 			return false;
 		}
 		$fields = array(
 				'gid' => (int)$_REQUEST['gid'],
 				'lid' => (int)$_REQUEST['lid'],
 				'cid' => (int)$_REQUEST['cid'],
 				'uuid' => (string)$_REQUEST['uuid'],
 		);
 		if($sign == md5(implode('&', $fields))){
 			return true;
 		}
 		return false;
 	}
 }