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
				$dataList = array();
				$assign = array(
						'fileName' => $fieldName,
						'dataList' => $dataList,
				);
				Lib_Smarty::gadminSmarty()->assign($assign);
				Lib_Smarty::gadminSmarty()->display('_px_header.html');
				Lib_Smarty::gadminSmarty()->display('gameconfig/roomlist.html');
				Lib_Smarty::gadminSmarty()->display('_px_footer.html');
				Common_Log::dump($assign);
				
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
				$cmConfig = Admin_Common::Single()->getConfig('common');
				$roomConfig = $cmConfig['roomConfig'];
				$assign = array(
						'fileName' => $fieldName,
						'roomConfig' => $roomConfig,
				);
				Lib_Smarty::gadminSmarty()->assign($assign);
				Lib_Smarty::gadminSmarty()->display('gameconfig/addroom.html');
				break;
				
			case 'saveAddItem':
				$modleData = $_REQUEST['modleData'];
				$table = Model_Table::bkRoom();
				$ckWhere = array("serverid = '{$modleData['serverid']}'");
				if(empty($modleData['serverid'])){
					$ret = array('code'=>'error', 'msg'=>"serverid: {$modleData['serverid']} 不能为空");
					echo json_encode($ret);
					die();
				}
				
				$check = Model_Db::dbBk()->getData($table, false, array('*'), $ckWhere);
				if($check['serverid']){
					$ret = array('code'=>'error', 'msg'=>"serverid: {$modleData['serverid']} 已存在");
					echo json_encode($ret);
					die();
				}
				
				$numData = array('serverid', 'screen', 'ptype', 'smallBlind', 'bigBlind', 'minLevel', 
						'maxLevel', 'minMoney', 'maxMoney', 'buyIn', 'autoIn', 'online', 'free',
						'readytime', 'acttime', 'judgetime', 'wexp', 'wexpBase', 'lexp', 'judgetime'
				);
				$strData = array('roomName');
				$insertData = array();
				foreach($modleData as $key=>$val){
					$insertData[$key] = (float)$val;
				}
				
				$flag = Model_Db::dbBk()->insertData($table, $insertData);
				if($flag){
					$ret = array(
							'code' => 'suc',
							'msg' => "serverid: {$modleData['serverid']} 添加成功!"
					);
				}else{
					$ret = array(
							'code' => 'error',
							'msg' => "serverid: {$modleData['serverid']} 添加失败!"
					);
				}
				echo json_encode($ret);
				die();
				break;
		}
		
		
	}
	
} 	// END CLASS