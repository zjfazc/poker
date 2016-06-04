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
 * @date: 2016年6月4日
 * @version: v1.0.0
 */
 defined( 'IN_GAME') or die( 'Comes Error!');
 

 class Lib_Smarty{
 	static public $_gInstance = array();
 	
 	/**
 	 * 单例
 	 * @return Smarty
 	 */
 	static public function smarty(){
 		$name = __FUNCTION__;
 		if(!isset(self::$_gInstance[$name])){
 			include_once PATH_LIB . 'include' .DS. 'smarty' .DS. 'Smarty.class.php';
 			self::$_gInstance[$name] = new Smarty();
 			$smartyPath = PATH_VIEW . 'smarty'. DS;
 			self::$_gInstance[$name]->config_dir = $smartyPath . "configure".DS; //配置文件目录
 			self::$_gInstance[$name]->template_dir = $smartyPath . "view".DS; //模板目录
 			self::$_gInstance[$name]->compile_dir = $smartyPath . "templates_c".DS; //模板编译目录
 			self::$_gInstance[$name]->cache_dir = $smartyPath . "cache".DS; //缓存目录
 			self::$_gInstance[$name]->left_delimiter = '<*'; //开始符
 			self::$_gInstance[$name]->right_delimiter = '*>'; //结束符
 			self::$_gInstance[$name]->force_compile = SERVER_ONLINE ? false : true; //强制重编译,上线后改为false
 			self::$_gInstance[$name]->compile_check = SERVER_ONLINE ? false : true; //检查模板改动,上线后改为false
 			self::$_gInstance[$name]->debugging = false; //打开调试
 			self::$_gInstance[$name]->debugging_ctrl = 'URL'; //调试方法
 			self::$_gInstance[$name]->use_sub_dirs = false; //编译和缓存可以分子目录
 		}
 		return self::$_gInstance[$name];
 	}
 	
 	/**
 	 *  管理后台单例
 	 * @return Smarty
 	 */
 	static public function gadminSmarty(){
 		$name = __FUNCTION__;
 		if(!isset(self::$_gInstance[$name])){
 			include_once PATH_LIB . 'include' .DS. 'smarty' .DS. 'Smarty.class.php';
 			self::$_gInstance[$name] = new Smarty();
 			$smartyPath = PATH_GADMIN.   'smarty'. DS;
 			self::$_gInstance[$name]->config_dir = $smartyPath . "configure".DS; //配置文件目录
 			self::$_gInstance[$name]->template_dir = PATH_GADMIN. 'view'.DS; //模板目录
 			self::$_gInstance[$name]->compile_dir = $smartyPath . "templates_c".DS; //模板编译目录
 			self::$_gInstance[$name]->cache_dir = $smartyPath . "cache".DS; //缓存目录
 			self::$_gInstance[$name]->left_delimiter = '<*'; //开始符
 			self::$_gInstance[$name]->right_delimiter = '*>'; //结束符
 			self::$_gInstance[$name]->force_compile = SERVER_ONLINE ? false : true; //强制重编译,上线后改为false
 			self::$_gInstance[$name]->compile_check = SERVER_ONLINE ? false : true; //检查模板改动,上线后改为false
 			self::$_gInstance[$name]->debugging = false; //打开调试
 			self::$_gInstance[$name]->debugging_ctrl = 'URL'; //调试方法
 			self::$_gInstance[$name]->use_sub_dirs = false; //编译和缓存可以分子目录
 		}
 		return self::$_gInstance[$name];
 	}
 }