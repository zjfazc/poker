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
 	
 	/**
 	 * 用户注册表
 	 * @return string
 	 */
 	static public function mregister(){
 		return "texas_member.mregister";
 	}
 	/**
 	 * 	用户客户端资料表
 	 * @param unknown $mid
 	 * @return string
 	 */
 	static public function mclient($mid){
 		$idx = $mid % 10;
 		$idx = "";
 		return "texas_member.mclient{$idx}";
 	}
 	/**
 	 * 用户基础资料表
 	 * @param unknown $mid
 	 * @return string
 	 */
 	static public function minfo($mid){
 		$idx = $mid % 10;
 		$idx = "";
 		return "texas_member.minfo{$idx}";
 	}
 	/**
 	 * 用户资产表
 	 * @param unknown $mid
 	 * @return string
 	 */
 	static public function masset($mid){
 		$idx = $mid % 10;
 		$idx = "";
 		return "texas_member.masset{$idx}";
 	}
 	/**
 	 * 
 	 * @param unknown $mid
 	 * @return string
 	 */
 	static public function mplaytexas($mid){
 		$idx = $mid % 10;
 		$idx = "";
 		return "texas_member.mplaytexas{$idx}";
 	}
 	
 	
 	/***********************************  LogTable  **************************************/
 	/**
 	 * 在线验证表
 	 * @return string
 	 */
 	static public function monline(){
 		return "texas_log.monline";
 	}
 	
 	
 	/***********************************  BkTable  **************************************/
 	/**
 	 * 配置备份表
 	 * @return string
 	 */
 	static public function bkRoom(){
 		return "texas_bk.room";
 	}
 	
 	
 	
 	
 }	// end CLASS