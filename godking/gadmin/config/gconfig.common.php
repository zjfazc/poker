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
 * @date: 2016年6月18日
 * @version: v1.0.0
 */
 
defined( 'IN_GAME') or die( 'Comes Error!');

$gdCommon = array();


////////  房间配置-场景ID   //////// 
$gdCommon['roomConfig']['screen'] = array(
		'1' => '低级场',
		'2' => '中级场',
		'3' => '高级场',
);

////////  房间配置-场景ID   //////// 
$gdCommon['roomConfig']['ptype'] = array(
		'1' => '5人',
		'2' => '7人',
		'3' => '9人',
);

////////  房间配置-场景ID   ////////
$gdCommon['roomConfig']['online'] = array(
		'1' => '上线',
		'0' => '下线',
);

return $gdCommon;