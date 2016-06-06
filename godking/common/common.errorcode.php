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
 
 class Common_Errorcode{
 	
 	const SUCCEED = array('0', '成功');
 	const PARAM_ERROR = array('-1', '参数错误');
 	
 	/**
 	 * 注册基本错误
 	 * @return multitype:multitype: string
 	 */
 	static public function regError(){
 		$ret = array(
 				'flag' => self::PARAM_ERROR,
 				'data' => array(),
 		);
 		return $ret;
 	}
 }