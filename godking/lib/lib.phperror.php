<?php
/**
 * Comment:
 * php报错日志记录类
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: Kume
 * @date: 2016年5月28日
 * @version: v1.0.0
 */
defined( 'IN_GAME') or die( 'Comes Error!');

function shutdown(){ //捕获错误
	$aError = error_get_last();
	if ((!empty( $aError)) && ($aError['type'] !== E_NOTICE)) {
		$date = date( 'Ymd');
		$file = PATH_LOG . 'phperror/' . $date . '.txt';
		$error = '';
		if ( ! file_exists( $file)) {
			$error = "<?php\nexit();\n";
		}
		if ( ! is_dir( PATH_LOG . 'phperror')) {
			mkdir( PATH_LOG . 'phperror',  0777);
		}
		$error .= date( 'Y-m-d H:m:s') . '---';
		$error .= 'Error:' . $aError['message'] . '--';
		$error .= 'File:' . $aError['file'] . '--';
		$error .= 'Line:' . $aError['line'];
		
		@file_put_contents( $file, $error . " \n ", FILE_APPEND | LOCK_EX);
		exit();
	}
}
function myErrorHandler($errno, $errstr, $errfile, $errline){
	if ($errno != E_NOTICE) {
		$date = date( 'Ymd');
		$file = PATH_LOG . '/phperror/' . $date . '.txt';
		$error = '';
		if ( ! file_exists( $file)) {
			$error = "<?php\nexit();\n";
		}
		if ( ! is_dir( PATH_LOG . 'phperror')) {
			mkdir( PATH_LOG . 'phperror',  0777);
		}
		$error .= date( 'Y-m-d H:m:s') . '---';
		$error .= 'Error:' . $errstr . '--';
		$error .= 'File:' . $errfile . '--';
		$error .= 'Line:' . $errline;
		$file = PATH_LOG . 'phperror/' . $date . '.txt';
		@file_put_contents( $file, $error . " \n ", FILE_APPEND | LOCK_EX);
	}
}
//注册错误函数
set_error_handler( 'myErrorHandler');
register_shutdown_function( 'shutdown');
