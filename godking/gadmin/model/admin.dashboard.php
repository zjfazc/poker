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
 * @date: 2016年6月12日
 * @version: v1.0.0
 */
 
defined( 'IN_GAME') or die( 'Comes Error!');

class Admin_Dashboard{
	private static $gObj = array ();
	/**
	 * 单例
	 *
	 * @return Admin_User
	*/
	static public function Single() {
		$name = __FUNCTION__;
		if (! isset ( self::$gObj [$name] )) {
			$class = __CLASS__;
			self::$gObj [$name] = new $class ();
		}
		return self::$gObj [$name];
	}
	
	/**
	 * 首页
	 * @param unknown $params
	 */
	public function actionIndex(){
		
		Lib_Smarty::gadminSmarty()->display('_px_header.html');
		Lib_Smarty::gadminSmarty()->display('demo.html');
		Lib_Smarty::gadminSmarty()->display('_px_footer.html');
		
// 		Lib_Smarty::gadminSmarty()->display('cm_header.html');
// 		Lib_Smarty::gadminSmarty()->display('demo.html');
// 		Lib_Smarty::gadminSmarty()->display('cm_footer.html');
	}
}