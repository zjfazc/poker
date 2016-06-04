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
 * @date: 2016年5月30日
 * @version: v1.0.0
 */
 defined( 'IN_GAME') or die( 'Comes Error!');
 
 CLASS Model_Table {
 	static private $_instance = array();
 	static public function Pre_Database(){
 		return '';
 	}
 	
 	/**
 	 * 工厂模式
 	 * @return
 	 */
 	static public function factory(){
 		$class = __CLASS__;
 		if(!isset(self::$_instance[$class])){
 			self::$_instance[$class] = new $class();
 		}
 		return self::$_instance[$class];
 	}
 	
 	static public function mclient(){
 		return "texas_member.mclient";
 	}
 	/**
 	 * 用户信息表
 	 * @param unknown $mid
 	 * @return string
 	 */
 	static public function minfo($mid){
 		$idx = $mid % 10;
 		return "texas_member.minfo{$mid}";
 	}
 	
 	
 	
 	
 	
 	
 	
 	
 }	// end CLASS