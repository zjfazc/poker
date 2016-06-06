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
 class Model_Member{
 	private $_mcacheOuttime = 2592000;	// 用户表缓存时间30天
 	
 	static private $gObj = array();
 	/**
 	 * 单例
 	 * @return Model_Member
 	*/
 	static public function Single(){
 		$name = __FUNCTION__;
 		if(!isset(self::$gObj[$name])){
 			$class = __CLASS__;
 			self::$gObj[$name] = new $class();
 		}
 		return self::$gObj[$name];
 	}
 	
 	/**
 	 * 通过平台id查找用户mid
 	 * @param unknown $lid
 	 * @param unknown $sitemid
 	 * @return int $mid
 	 */
 	public function sitemidToMid($lid, $sitemid){
 		if(empty($lid) || empty($sitemid)){
 			return 0;
 		}
 		$key = Model_Key::sitemidToMid($lid, $sitemid);
 		$mid = (int)Model_Cache::redisMain()->get($key);
 		if(empty($mid)){
 			$table = Model_Table::mregister();
 			$get = Model_Db::dbMain()->getData($table, false, array('mid'), array("lid='{$lid}'", "sitemid='{$sitemid}'"));
 			$mid = $get ? $get['mid'] : 0;
 			if($mid){
 				Model_Cache::redisMain()->setex($key, $mid, $this->_mcacheOuttime);
 			}
 		}
 		return $mid;
 	}
 	/**
 	 * 用户注册
 	 * @param unknown $info
 	 * @return boolean|multitype:string number unknown
 	 */
 	public function playRegister($info){
 		$ret = false;
 		if(empty($info)){
 			return $ret;
 		}
 		///////  初始化数据   //////
 		$user = array();
 		$user['lid'] = LOGINID;
 		$user['sitemid'] = $info['sitemid'];
 		$user['regtime'] = NOW;
 		$user['name'] = $info['name'];
 		$user['gender'] = $info['name']	;
 		$user['icon'] = '';	
 		$user['money'] = 0;
 		$user['exp'] = 0;
 		$user['level'] = 0;
 		$user['twin'] = 0;
 		$user['tlose'] = 0;
 		$user['tmaxCard'] = 0;
 		$user['tmaxWin'] = 0;

 		/**********************   用户数据生成     ************************/
 		/////////    注册     /////////
 		$regTb = Model_Table::mregister();
 		Model_Db::dbMain()->Start();
 		$sqlReg  = "INSERT INTO {$regTb} 
 							SET lid='{$user['lid']}', sitemid='{$user['sitemid']}',regtime={$user['regtime']} ";
 		$query = Model_Db::dbMain()->query($sqlReg);
 		if(empty($query)){
 			Model_Db::dbMain()->Rollback();
 			return false;
 		}
 		$mid = Model_Db::dbMain()->insertID();
 		$user['mid'] = $mid;
 		
 		/////////    基本资料     /////////
 		$infoTb = Model_Table::minfo($mid);
 		$sqlInfo = "INSERT INTO {$infoTb} 
 							SET mid='{$mid}', name='{$user['name']}', gender='{$user['gender']}', icon='{$user['icon']}'";
 		if(!Model_Db::dbMain()->query($sqlInfo) || Model_Db::dbMain()->affectedRows()<=0){
 			Model_Db::dbMain()->Rollback();
 			return false;
 		}
 		
 		/////////    用户资产     /////////
 		$assetTb = Model_Table::masset($mid);
 		$sqlAsset = "INSERT INTO {$assetTb}
 								SET mid='{$mid}', money='{$user['money']}', exp='{$user['exp']}', level='{$user['level']}' ";
 		if(!Model_Db::dbMain()->query($sqlAsset) || Model_Db::dbMain()->affectedRows()<=0){
 			Model_Db::dbMain()->Rollback();
 			return false;
 		}
 		
 		/////////    用户资产     /////////
 		$ptexasTb = Model_Table::mplaytexas($mid);
 		$sqlTexas = "INSERT INTO {$ptexasTb}
 								SET mid='{$mid}', twin='{$user['twin']}', tlose='{$user['tlose']}', 
 								tmaxCard='{$user['tmaxCard']}' , tmaxWin='{$user['tmaxWin']}' ";
 		if(!Model_Db::dbMain()->query($sqlTexas) || Model_Db::dbMain()->affectedRows()<=0){
 			Model_Db::dbMain()->Rollback();
 			return false;
 		}					
 		
 		/////////    提交数据     /////////
 		Model_Db::dbMain()->Commit();
 		
 		//////  保存映射key  //////
 		$sitemidToMid = Model_Key::sitemidToMid($user['lid'], $user['sitemid']);
 		Model_Cache::redisMain()->setex($sitemidToMid, $mid, $this->_mcacheOuttime);
 		return $user;
 	}
 	
 	/**
 	 * 保存Member缓存表
 	 * @param unknown $mid
 	 * @param unknown $data
 	 * @return boolean
 	 */
 	public function setMemberHash($mid, $data){
 		if(empty($mid) || empty($data)){
 			return  false;
 		}
 		$hashname = Model_Key::member($mid);
 		return Model_Cache::redisMinfo($mid)->hMset($hashname,  $data);
 	}
 	
 	/**
 	 * 读取Member缓存表
 	 * @param unknown $mid
 	 * @param unknown $field
 	 * @return boolean|Ambigous <multitype:, unknown>
 	 */
 	public function getMemberHash($mid, $field=array()){
 		if(empty($mid) ){
 			return  array();
 		}
 		$hashname = Model_Key::member($mid);
 		if(empty($field)){
 			return Model_Cache::redisMinfo($mid)->hGetAll($hashname);
 		}else{
 			return Model_Cache::redisMinfo($mid)->hMget($hashname, $field); 
 		}
 	}
 	
 	/**
 	 * 查询用户member库
 	 * @param unknown $mid
 	 * @param unknown $table
 	 * @return multitype:|Ambigous <multitype:, Ambigous>
 	 */
 	public function getMemberTable($mid, $table){
 		$ret = array();
 		if(empty($mid) || empty($table)){
 			return $ret;
 		}
 		switch($table){
 			case Model_Table::mregister():
 				$fields = array('lid', 'sitemid', 'regtime');
 				break;
 				
 			case Model_Table::minfo($mid):
 				$fields = array('name', 'gender', 'icon');
 				break;
 				
 			case Model_Table::masset($mid):
 				$fields = array('money', 'exp', 'level');
 				break;	
 			
 			case Model_Table::mplaytexas($mid):
 				$fields = array('twin', 'tlose', 'tmaxCard', 'tmaxWin');
 				break;
 		}
 		
 		$ret = $this->getMemberHash($mid, $fields);
 		$dataMiss = false;
 		foreach ($ret as $val=>$key){
 			if(false === $key){
 				$dataMiss = true;
 				break;
 			}
 		}
 		if($dataMiss){
 			$ret = Model_Db::dbMain()->getData($table, false, $fields, array("mid={$mid}"));
 			$this->setMemberHash($mid, $ret);
 		}
 		return $ret;
 	}
 	
 	/**
 	 * 查询用户的游戏资料
 	 * @param unknown $mid
 	 * @return multitype:|Ambigous <multitype:unknown , multitype:, unknown>
 	 */
 	public function getPlayerByMid($mid){
 		$ret = array();
 		if(empty($mid)){
 			return $ret;
 		}
 		$hashname = Model_Key::member($mid);
 		$ret = Model_Cache::redisMinfo($mid)->hGetAll($hashname);
 		if(empty($ret)){	// 没有数据， 遍历数据表
 			$needTables = array(
 					Model_Table::mregister(),
 					Model_Table::masset($mid),
 					Model_Table::minfo($mid),
 					Model_Table::mplaytexas($mid),
 			);
 			$ret = array('mid' => $mid);
 			foreach($needTables as $table){
 				$get = $this->getMemberTable($mid, $table);
 				$ret = array_merge($ret, $get);
 			}
 		}
 		return $ret;
 	}
 	
 }	// END CLASS