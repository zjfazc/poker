<?php
/**
 * Comment:
 * 日志操作类
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
 
 /**
  * 日志操作类
  * @author Kume
  *
  */
 class Common_Log{
 	
 	/**
 	 * 格式化打印显示数据
 	 * @param unknown $data
 	 */
 	static public function dump($data, $line='no define'){
 		echo "<div>--------- @at line:: {$line}  ----------<br/><pre>";
 		var_dump($data);
 		echo '</pre></div>';
 	}
 }