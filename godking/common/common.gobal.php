<?php
/**
 * Comment:
 * 全局操作类： 操作配置，读取配置
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
 
 class Common_Gobal{
 	static public $_gDataFile = array();	// 配置文件
 	static public $_gCommonConfig = array();
 	static public $_gGameConfig = array();
 	
 	/**
 	 * 初始化游戏公共配置
 	 * @return multitype:
 	 */
 	static public function initCommonConfig(){
 		if(empty(self::$_gCommonConfig)){
 			self::$_gCommonConfig = require_once PATH_ROOT . 'config/config.common.php';
 		}
 		return self::$_gCommonConfig;
 	}
 	
 	/**
 	 * 初始化游戏配置
 	 * @return multitype:
 	 */
 	static public function initGameConfig(){
 		if(empty(self::$_gGameConfig)){
 			self::$_gGameConfig = require_once PATH_CONFIG . 'config.game.php';
 		}
 		return self::$_gGameConfig;
 	}
 	
 	static public function dataFile($name){
 		
 	}
 	
 	/**
 	 * 
 	 */
 	static public function header(){
 		header( "Content-Type:text/html; charset=utf-8" );
 	}
 	
 	/**
 	 * 
 	 */
 	static public function nocache(){
 		header("Pragma:no-cache");
 		header("Cache-Type:no-cache, must-revalidate");
 		header("Expires: -1");
 	}
 	
 }