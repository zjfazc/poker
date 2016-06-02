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
defined ( 'IN_GAME' ) or die ( 'Comes Error!' );
class Common_Function {
	
	/**
	 * 取正整数
	 *
	 * @param unknown $num        	
	 * @return mixed
	 */
	static public function uint($num) {
		return max ( 0, ( int ) $num );
	}
	
	/**
	 * 安全性检测.调用escape存入的,一定要调unescape取出
	 * @param unknown $string
	 * @return string
	 */
	static public function escape($string) {
		return addslashes ( trim ( $string ) );
	}
	
	/**
	 * 安全性检测.调用escape存入的,一定要调unescape取出
	 * @param unknown $string
	 * @return string
	 */
	static public function unescape($string) {
		return stripslashes ( $string );
	}
	
}