<?php
/**
 * Comment:
 * 用户在线状态控制类
 * ==============================================
 * Copy right 2016-2017 
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: Kume
 * @date: 2016年6月17日
 * @version: v1.0.0
 */
 
defined( 'IN_GAME') or die( 'Comes Error!');
class Model_Monline{
	
	private static $gObj = array ();
	/**
	 * 单例
	 *
	 * @return Model_Monline
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
	 * 设置mtkey
	 * @param unknown $mid
	 * @return string
	 */
	public function setMtkey($mid){
		if(empty($mid)){
			return "";
		}
		$secret = "loveit";
		$mltime = NOW;
		$mtkey = base64_decode($mid. "-". md5($mid.$secret.$mltime));
		$table = Model_Table::monline();
		$sql = "INSERT INTO {$table} SET mid='{$mid}', mtkey='{$mtkey}', mltime='{$mltime}'
					ON DUPLCATE KEY UPDATE
					mtkey='{$mtkey}', mltime='{$mltime}' ";
		Model_Db::dbLog()->query($sql);
		if(Model_Db::dbLog()->affectedRows()>0){
			$key = Model_Key::monline($mid);
			Model_Cache::redisMonline()->setex($key, $mtkey, 86400);
			return $mtkey;
		}
		return "";
	}
	
	/**
	 * 查找mtkey
	 * @param unknown $mid
	 * @return string
	 */
	public function getMtkey($mid){
		if(empty($mid)){
			return "";
		}
		$key = Model_Key::monline($mid);
		$mtkey = Model_Cache::redisMonline()->get($key);
		if(empty($mtkey)){
			$table = Model_Table::monline();
			$get = Model_Db::dbLog()->getData($table, false, array('mtkey'), array('mid'=>$mid));
			$mtkey = isset($get['mtkey']) ? $get['mtkey'] : "";
			if($mtkey){
				Model_Cache::redisMonline()->setex($key, $mtkey, 86400);
			}
		}
		return $mtkey;
	}
	
	/**
	 * 验证mtkey
	 * @param unknown $mid
	 * @param unknown $checkMtkey 客户端传过来的mtkey
	 * @return boolean
	 */
	public function checkMonline($mid, $checkMtkey){
		if(empty($mid) || empty($checkMtkey)){
			return false;
		}
		$mtkey = $this->getMtkey($mid);
		if($checkMtkey == $mtkey){
			return true;
		}
		return false;
	}
	
	/**
	 * 从mtkey解析出mid
	 * @param unknown $mtkey
	 * @return boolean|number
	 */
	public function decodeMtkeyToMid($mtkey){
		if(empty($mtkey)){
			return false;
		}
		$decode = explode('-', base64_encode($mtkey));
		$mid = isset($decode[0]) ? $decode[0] : 0;
		return $mid;
	}
	
} // END CLASS