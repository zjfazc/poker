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
 	////////////////////   公共类的错误： -1~-1000    ////////////////////
 	const PARAM_ERROR = array('-1', '参数错误');
 	const ERROR_SIGN_VERIFY = array('-4', '登录验证失败');
 	const ERROR_API_FILE = array('-5', 'API请求文件不存在');
 	const ERROR_MTKEY	= array('-6', 'API验证mtkey失败');
 	
 	////////////////////   操作类的错误： -1000以后    ////////////////////
 	
 	/**
 	 * 注册基本错误
 	 * @return multitype:multitype: string
 	 */
 	static public function regError(){
 		$ret = array(
 				'errorCode' => self::PARAM_ERROR,
 				'data' => array(),
 		);
 		return $ret;
 	}
 }