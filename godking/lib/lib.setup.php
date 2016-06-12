<?php
/**
 * Comment:
 * 加载文件控制器
 * ==============================================
 * Copy right 2016-2017 
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: Kume
 * @date: 2016年5月28日
 * @version: v1.0.0
 */
defined ( 'IN_GAME' ) or die ( 'Comes Error!' );
final class Lib_Setup {
	// 定义使用自动加载目录文件,之后需要加目录遍历直接加数组元素
	protected static $_paths = array (
			'model' => PATH_MODEL,
			'common' => PATH_COMMON,
			'lib' => PATH_LIB 
	);
	protected static $_files = array ();
	public static $ext = '.php';
	protected static $_init = FALSE;
	
	/**
	 * 注册自动加载脚本
	 * 
	 * @return boolean
	 */
	static public function init() {
		if (self::$_init) {
			return true;
		}
		self::$_init = TRUE;
		spl_autoload_register ( array (
				'Lib_Setup',
				'load' 
		) );
	}
	
	/**
	 * 加载方法
	 * 
	 * @param unknown $class        	
	 * @return boolean
	 */
	static public function load($class) {
		$pos = strrpos ( $class, '_' );
		$dir = "";
		if ($pos > 0) {
			$classData = explode ( "_", $class );
			$dir = lcfirst ( $classData [0] );
		} else {
			$dir = "{$class}";
		}
		
		$file = str_replace ( '_', '.', strtolower ( $class ) );
		if ($file = self::findFile ( $dir, $file )) {
			require ($file);
			return true;
		}
	}
	
	/**
	 * 查找文件
	 * 
	 * @param unknown $dir
	 *        	目录名字
	 * @param unknown $file
	 *        	文件名字
	 * @param string $ext
	 *        	拓展名
	 * @return string
	 */
	static public function findFile($dir, $file, $ext = NULL) {
		$ext = $ext ? ".{$ext}" : self::$ext;
		$found = "";
		
		if (isset ( self::$_paths [$dir] )) {
			$filePath = self::$_paths [$dir] . $file . $ext;
			$key = md5 ( $filePath );
			if (isset ( self::$_files [$key] )) {
				$found = self::$_files [$key];
			} else {
				if (is_file ( $filePath )) {
					self::$_files [$key] = $filePath;
					$found = self::$_files [$key];
				}
			}
		}
		return $found;
	}
	
	/**
	 * 添加文件加载目录
	 * 
	 * @param unknown $dirname
	 *        	目录名字
	 * @param unknown $path
	 *        	目录绝对地址
	 * @return boolean
	 */
	static public function setPath($dirname, $path) {
		if (! in_array ( $path, self::$_paths )) {
			self::$_paths [$dirname] = $path;
		}
		return true;
	}
}	// END CLASS