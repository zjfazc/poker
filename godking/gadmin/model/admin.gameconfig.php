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
	
	/**
	 * 房间配置
	 */
	public function actionRoom(){
		$act = isset($_REQUEST['gAct']) ? trim($_REQUEST['gAct']) : 'getlist';
		switch($act){
			case 'getlist':	// 查看
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
				$dataList = array();
				$table = Model_Table::bkRoom();
				$list = Model_Db::dbBk()->getData($table, true, array("*"));
				foreach($list as $val){
					$tmp = array();
					foreach ($fieldName as $k=>$name){
						if(in_array($k, array('online', 'ptype', 'screen'))){
							$tmp[$k] = $val[$k] . " . " . $roomConfig[$k][$val[$k]];
						}else if(is_numeric($val[$k])){
							$tmp[$k] = number_format($val[$k]);
						}else {
							$tmp[$k] = Common_Function::single()->unescape($val[$k]);
						}
 					}
 					$tmp['id'] = $val['id'];
 					$dataList[$val['id']] = $tmp;
				}
				
				$assign = array(
						'fileName' => $fieldName,
						'dataList' => $dataList,
				);
				Lib_Smarty::gadminSmarty()->assign($assign);
				Lib_Smarty::gadminSmarty()->display('_px_header.html');
				Lib_Smarty::gadminSmarty()->display('gameconfig/roomlist.html');
				Lib_Smarty::gadminSmarty()->display('_px_footer.html');
// 				Common_Log::dump($assign);
				break;
				
			case 'addItem':	// 添加页面
				$cmConfig = Admin_Common::Single()->getConfig('common');
				$roomConfig = $cmConfig['roomConfig'];
				$assign = array(
						'roomConfig' => $roomConfig,
				);
				Lib_Smarty::gadminSmarty()->assign($assign);
				Lib_Smarty::gadminSmarty()->display('gameconfig/addroom.html');
				break;
				
			case 'saveAddItem':	// 添加数据
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
				
				////  字段处理  ////
				$numData = array('serverid', 'screen', 'ptype', 'smallBlind', 'bigBlind', 'minLevel', 
						'maxLevel', 'minMoney', 'maxMoney', 'buyIn', 'autoIn', 'online', 'free',
						'readytime', 'acttime', 'judgetime', 'wexp', 'wexpBase', 'lexp', 'judgetime'
				);
				$strData = array('roomName');
				$insertData = array();
				foreach($modleData as $key=>$val){
					if(in_array($key, $numData)){
						$insertData[$key] = (float)$val;
					}else if(in_array($key, $strData)){
						$insertData[$key] = (string)$val;
					}
				}
				$flag = Model_Db::dbBk()->insertData($table, $insertData);
				if($flag){
					$ret = array(
							'code' => 'suc',
							'msg' => "serverid: {$modleData['serverid']} 添加成功! 查看数据请刷新!"
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
				
			case 'editItem': // 编辑页面
				$id = Common_Function::single()->uint($_REQUEST['id']);
				$table = Model_Table::bkRoom();
				$get = Model_Db::dbBk()->getData($table, false, array('*'), array("id='{$id}'"));
				$cmConfig = Admin_Common::Single()->getConfig('common');
				$roomConfig = $cmConfig['roomConfig'];
				$assign = array(
						'roomConfig' => $roomConfig,
						'data' => $get,
				);
				Lib_Smarty::gadminSmarty()->assign($assign);
				Lib_Smarty::gadminSmarty()->display('gameconfig/editroom.html');
				break;	
				
			case 'saveEditItem': // 保存编辑数据
				$modleData = $_REQUEST['modleData'];
				$id = $modleData['id'];
				if(empty($id)){
					$ret = array('code'=>'error', 'msg'=>"serverid: {$modleData['serverid']} 不能为空");
					echo json_encode($ret);
					die();
				}
				unset($modleData['id']);
				$table = Model_Table::bkRoom();
				$flag = Model_Db::dbBk()->updateData($table, $modleData, array("id='{$id}'"));
				$ret = array(
						'code' => 'suc',
						'msg' => "serverid: {$modleData['serverid']} 修改成功!"
				);
				echo json_encode($ret);
				die();
				break;
				
			case 'deleteItem': // 删除
				$id = Common_Function::single()->uint($_REQUEST['id']);
				if(empty($id)){
					$ret = array('code'=>'error', 'msg'=>"serverid: 不能为空");
					echo json_encode($ret);
					die();
				}
				$table = Model_Table::bkRoom();
				$sql = "DELETE FROM {$table} WHERE id='{$id}'";
				Model_Db::dbBk()->query($sql);
				if(Model_Db::dbBk()->affectedRows() > 0){
					$ret = array(
							'code' => 'suc',
							'msg' => "serverid: {$id} 删除成功!"
					);
				}else {
					$ret = array(
							'code' => 'error',
							'msg' => "serverid: {$id} 删除失败!"
					);
				}
				echo json_encode($ret);
				die();
				break;
		}
	}
	
	/**
	 * 创建房间配置
	 */
	public function actionRoomMkConfig(){
		$pathPhp = PATH_CONFIG . 'data' . DS;
		$pathJson = PATH_CDN . 'json' . DS;
		$mkname = 'room';
		$version = date('YmdHi');
		$phpFile = $pathPhp . "data.{$mkname}.php";
		$jsonFile = $pathJson . "{$mkname}.json";
		
		$table = Model_Table::bkRoom();
		$data = Model_Db::dbBk()->getData($table, true, array("*"), array(), array('serverid asc'));
		$jsonOut = array();	// json配置内容
		$phpOut = "<?php defined( 'IN_GAME') or die( 'Comes Error!'); \n";	// php配置内容
		$phpOut .= "\$room = array(); \n";
		
// 		$cmConfig = Admin_Common::Single()->getConfig('common');
// 		$roomConfig = $cmConfig['roomConfig'];
		foreach($data as $val){
			unset($val['id']);
			if(empty($val['status'])){
				$jsonOut[] = $val;
			}
			$phpTmp = array();
			foreach($val as $k=>$v){
				$phpTmp[] = "'{$k}'=>'{$v}'";
				$jsTmp[] = "";
			}
			$phpOut .= "\$room[{$val['serverid']}] = array(" . implode(',', $phpTmp) . "); \n";
			
			////  保存到redis  ////
			$serverKey = Model_Key::serRoom($val['serverid']);
			Model_Cache::redisSystem()->hMset($serverKey, $val);
		}
		$phpOut .= "return \$room; \n";
		
		Common_Function::single()->mkConfigOutput($phpOut, $phpFile);
		Common_Function::single()->mkConfigOutput(json_encode($jsonOut), $jsonFile);
	}
	
} 	// END CLASS