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
 * @date: 2016年5月31日
 * @version: v1.0.0
 */
 defined( 'IN_GAME') or die( 'Comes Error!');
 
 $commonConfig = array();
 /////////////  游戏类型  //////////////
 $commonConfig['gids' ] = array(
 	1 => array('texas', '德州扑克') 	// 德州扑克安卓版
 );
 
 /////////////  登录方式  //////////////
 $commonConfig['lids'] = array(
 	1 => '游客模式',
 );
 return $commonConfig;