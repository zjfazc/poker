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
 
 CLASS Model_Core{
 	static private $_instance = array();
 	static public function factory(){
 		$class = __CLASS__;
 		echo "<br>now runing factory at class :: {$class} <br>";
 		if(!isset(self::$_instance[$class])){
 			self::$_instance[$class] = new $class();
 		}
 		var_dump(self::$_instance[$class]);
 		return self::$_instance[$class];
 	}
 }