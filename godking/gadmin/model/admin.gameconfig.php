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

class Admin_Gameconfig{
	
	private static $gObj = array ();
	/**
	 * 单例
	 *
	 * @return Admin_Gameconfig
	*/
	static public function Single() {
		$name = __FUNCTION__;
		if (! isset ( self::$gObj [$name] )) {
			$class = __CLASS__;
			self::$gObj [$name] = new $class ();
		}
		return self::$gObj [$name];
	}
	
	public function actionRoom(){
// 		CREATE TABLE `room` (
// 		`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键',
// 		`serverid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'SERVER房间等级（SERVER定义）',
// 		`screen` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '场景ID:1-低级场;2-中级场',
// 		`ptype` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '坐满类型：1-5人;2-7人;3-9人',
// 		`smallBlind` BIT(1) NOT NULL DEFAULT b'0' COMMENT '小盲注',
// 		`bigBlind` BIT(1) NOT NULL DEFAULT b'0' COMMENT '大盲注',
// 		`minLevel` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '进入等级下限',
// 		`maxLevel` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '进入等级上限',
// 		`minMoney` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '最小携带',
// 		`maxMoney` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '最大携带:0-无限制',
// 		`buyIn` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '默认带入',
// 		`autoIn` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '快速进入标准:低于该值进入该房',
// 		`free` INT(11) NOT NULL DEFAULT '0' COMMENT '台费',
// 		`readytime` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '开局准备时间',
// 		`acttime` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '玩牌操作时间',
// 		`judgetime` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '结算时间',
// 		`wexp` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '赢牌基本经验',
// 		`wexpBase` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '赢牌经验：人数基准',
// 		`lexp` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '输牌基本经验',
		
		$act = isset($_REQUEST['gAct']) ? trim($_REQUEST['gAct']) : 'getlist';
		switch($act){
			case 'getlist':
				$fieldName = array(
						'serverid' => 'SER房间等级',
						'roomName' => '房名称',
						'screen' => '场景',
						'ptype' => '坐满类型',
						'smallBlind' => '小盲注',
						'bigBlind' => '大盲注',
						'minLevel' => '等级下限',
						'maxLevel' => '等级上限',
						'minMoney' => '最小携带',
						'maxMoney' => '最大携带',
						'buyIn' => '默认带入',
						'autoIn' => '快速开始',
						'online' => '上线',
				);
				$assign = array(
						'fileName' => $fieldName,
						'dataList' => $dataList,
				);
				Lib_Smarty::gadminSmarty()->assign($assign);
				Lib_Smarty::gadminSmarty()->display('cm_header.html');
				Lib_Smarty::gadminSmarty()->display('gameconfig/roomlist.html');
				Lib_Smarty::gadminSmarty()->display('cm_footer.html');
				
				break;
				
			case 'addItem':
				$fieldName = array(
						'serverid' => 'SER房间等级',
						'roomName' => '房名称',
						'screen' => '场景',
						'ptype' => '坐满类型',
						'smallBlind' => '小盲注',
						'bigBlind' => '大盲注',
						'minLevel' => '等级下限',
						'maxLevel' => '等级上限',
						'minMoney' => '最小携带',
						'maxMoney' => '最大携带',
						'buyIn' => '默认带入',
						'autoIn' => '快速开始',
						'online' => '上线',
				);
				Lib_Smarty::gadminSmarty()->display('gameconfig/roomlist.html');
				break;
		}
		
		
	}
	
} 	// END CLASS