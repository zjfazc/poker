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
defined ( 'IN_GAME' ) or die ( 'Comes Error!' );

include_once '../init.php';
class gateway {
	public function __construct($params) {
		$dynamic = str_replace ( '\\', '', $params ['dynamic'] );
		$aParam = json_decode ( $dynamic, true );
		$aSv = explode ( '.', $params ['method'] );
		$class = $aSv [0];
		$function = $aSv [1];
		$file = WWWROOT . 'api/controller/' . $class . '.php';
		
		//////////  判斷文件是否存在  ///////////
		if(!is_file($file)){
			$errorCode = Common_Errorcode::ERROR_API_FILE;
			Common_Function::single()->sendOut($errorCode, array());
		}
		
		/////////////////    驗證秘鑰    ///////////////////
		$mtkey = $params['mtkey'];
		$mid = Model_Monline::Single()->decodeMtkeyToMid($mtkey);
		if( false == Model_Monline::Single()->checkMonline($mid, $mtkey)){
			$errorCode = Common_Errorcode::ERROR_MTKEY;
			Common_Function::single()->sendOut($errorCode, array());
		}
		
		/////////////////    檢查方法是否存在    /////////////////
		$params = array();
		$params['mid'] = $mid;
		include_once $file; 
		$obj = new $class();
		if(!method_exists($obj, $function)){
			Common_Function::single()->sendOut($errorCode, array());
		}
		/////////////  輸出  /////////////
		$result = $obj->$function($params);
		Common_Function::single()->sendOut($errorCode, $result);
	}
}

new gateway ( $_REQUEST );