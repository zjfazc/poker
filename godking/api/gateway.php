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
 
 include_once '../init.php';
 class gateway{
 	public function __construct($params){
 		
 		$dynamic = str_replace( '\\', '', $params['dynamic']);
 		$aParam = json_decode( $dynamic, true);
 		$aSv = explode( '.', $params['method']);
 		$class = $aSv[0];
 		$function = $aSv[1];
 		$file = WWWROOT . 'api/controller/' . $class . '.php';
 	}
 }
 
 new gateway( $_REQUEST);