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
 class Model_Key{
 	
 	/**
 	 * 用户资料数据缓存key
 	 * 使用hash存储
 	 * @param unknown $mid
 	 * @return string
 	 */
 	static public function member($mid){
 		return "member.{$mid}";
 	}
 	
 	/**
 	 * 平台号映射mid缓存key
 	 * @param unknown $lid
 	 * @param unknown $sitemid
 	 * @return string
 	 */
 	static public function sitemidToMid($lid, $sitemid){
 		return "sitemidToMid.{$lid}.{$sitemid}";
 	}
 	
 	/**
 	 * 用户mtkey验证码
 	 * @param unknown $mid
 	 * @return string
 	 */
 	static public function monline($mid){
 		return "monline.{$mid}";
 	}
 	
 	static public function memdata($mid){
 		return "memdata.{$mid}";
 	}
 	
 	
 	
 	/************************* 系统 配置key  ***************************/
 	/**
 	 * 房间配置缓存key
 	 * @param unknown $serverid
 	 * @return string
 	 */
 	static public function serRoom($serverid){
 		return "serRoom.{$serverid}";
 	}
 }