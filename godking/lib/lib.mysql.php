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
 * @date: 2016年5月29日
 * @version: v1.0.0
 */
defined ( 'IN_GAME' ) or die ( 'Comes Error!' );
class Lib_Mysql {
	private $host; // 主机
	private $port; // 端口
	private $user; // 用户
	private $password; // 密码
	private $dbname; // db名
	
	static private  $mysqli; // mysqli对象
	static $isConnected = false;
	/**
	 * 采用二维数组
	 * 参数 array ( array('192.168.1.239:3306', 'socialgame', 'socialgame', ''))
	 *
	 * @param unknown_type $servers        	
	 */
	public function __construct($servers) {
		$aHost = explode ( ':', $servers [0] );
		$this->host = $aHost [0];
		$this->port = isset ( $aHost [1] ) ? $aHost [1] : '3306'; // 默认端口3306
		$this->user = $servers [1];
		$this->password = $servers [2];
		$this->dbname = $servers [3];
	}
	/**
	 * 检查并连接数据库
	 *
	 * @return mysqli
	 */
	public function connect() {
		if (self::$isConnected) { // 如果已经连接
			return self::$mysqli;
		}
		if (! class_exists ( 'mysqli' )) {
			die ( 'This Lib Requires The Mysqli Extention!' );
		}
		
		$conn = mysqli_init ();
		mysqli_options ( $conn, MYSQLI_OPT_LOCAL_INFILE, true );
		mysqli_real_connect ( $conn, $this->host, $this->user, $this->password, $this->dbname, $this->port , null, 0 );
		
		self::$mysqli = $conn;
		$error = mysqli_connect_error ();
		if ($error) {
			$this->errorlog ( $error );
		}
		self::$isConnected = true;
		self::$mysqli->query ( "SET SQL_MODE='',CHARACTER_SET_CONNECTION='utf8',CHARACTER_SET_RESULTS='utf8',CHARACTER_SET_CLIENT='binary',NAMES 'utf8'" );
		return self::$mysqli;
	}
	/**
	 * 执行sql
	 *
	 * @param unknown_type $query        	
	 */
	public function query($query) {
		$this->connect ();
		$result = self::$mysqli->query ( $query );
		$error = mysqli_error ( self::$mysqli );
		if ($error) {
			$this->errorlog ( $error );
		}
		return $result;
	}
	// 查询一条记录
	public function getOne($query, $mode = MYSQL_BOTH) {
		$result = $this->query ( $query );
		if (! is_object ( $result )) {
			return array ();
		}
		return $result->fetch_array ( $mode );
	}
	// 查询多条记录
	public function getAll($query, $mode = MYSQL_BOTH) {
		$result = $this->query ( $query );
		if (! is_object ( $result )) {
			return array ();
		}
		$dataList = array ();
		while ( $row = $result->fetch_array ( $mode ) ) {
			$dataList [] = $row;
		}
		return $dataList;
	}
	
	/**
	 * 获取最新插入的记录ID
	 */
	public function insertID() {
		return self::$mysqli->insert_id;
	}
	/**
	 * sql执行的影响行数
	 */
	public function affectedRows() {
		return self::$mysqli->affected_rows;
	}
	/**
	 * 关闭数据库
	 */
	public function close() {
		if (self::$isConnected) {
			self::$isConnected = false;
			self::$mysqli->close ();
		}
	}
	/**
	 * 安全性检测.调用escape存入的,一定要调unescape取出
	 */
	public function escape($string) {
		return addslashes ( trim ( $string ) );
	}
	/**
	 * 安全性检测.调用escape存入的,一定要调unescape取出
	 * @param unknown $string
	 * @return string
	 */
	public function unescape($string) {
		return stripslashes ( $string );
	}
	/**
	 * 事务处理章节
	 */
	public function Start() {
		$this->connect ();
		self::$mysqli->autocommit ( FALSE );
	}
	public function Commit() {
		self::$mysqli->commit ();
	}
	public function CommitId() {
		$aId = $this->getOne ( 'SELECT LAST_INSERT_ID()', MYSQL_NUM );
		return ( int ) $aId [0];
	}
	public function Rollback() {
		self::$mysqli->rollback ();
	}
	private function errorlog($msg = '') {
		$error = date ( 'H:i:s' ) . ":\n" . mysqli_errno ( self::$mysqli ) . ":\nmsg:" . $msg . ";\n";
		echo "<p>{$error}</p>";
// 		oo::funModel ( 'logs' )->debug ( $error, 'mysql.txt' );
		die ( 'DB Invalid!!!' );
	}
	
	/**************************   数据库特殊处理   ****************************/
	/**
	 * 基础插入数据
	 * @param unknown $table
	 * @param unknown $data 数据格式，记得处理
	 * @param string $returnId  是否返回自增插入数据id
	 * @return boolean|unknown
	 */
	public function insertData($table, $data, $returnId=false){
		if(empty($table) || empty($data)){
			return false;
		}
		$setField = array();
		foreach($data as $key=>$val){
			if(is_string($val)){
				$setField[] = "`{$key}`='".$this->escape($val)."'";
			}else {
				$setField[] = "`{$key}`='{$val}'";
			}
		}
		$query = "INSERT INTO {$table} SET ". implode(',', $setField);
		$flag = $this->query($query);
		if($flag && $returnId){
			$flag = $this->insertID();
		}
		return $flag;
	}
	
	/**
	 *  基础更新数据
	 * @param unknown $table 数据表
	 * @param unknown $data 修改字段数组值
	 * @param unknown $where  条件数组
	 * @param number $limit 数量
	 * @return boolean|unknown
	 */
	public function updateData($table, $data, $where, $limit=0){
		if(empty($table) || empty($data) || empty($where)){
			return false;
		}
		
		$setField = array();
		foreach($data as $key=>$val){
			if(is_string($val)){
				$setField[] = "`{$key}`='".$this->escape($val)."'";
			}else {
				$setField[] = "`{$key}`='{$val}'";
			}
		}
		
		$limitField = $limit ? " LIMIT {$limit} " : "";
		$query = "UPDATE {$table} SET ". implode(',', $setField)
						. " WHERE " .  implode(' AND ', $where)
						. $limitField;
		$this->query($query);
		$flag = $this->affectedRows();
		return $flag;
	}
	
	/**
	 * 基础查询数据操作
	 * @param unknown $table
	 * @param string $all 是否全部， getAll  or  getOne
	 * @param unknown $select  array('*', 'field1', 'field2')
	 * @param unknown $where  array('a>b', 'c=e', 'd<f')
	 * @param unknown $order  array('field1 asc', 'field2 desc')
	 * @param unknown $limit  array('0', '100')
	 * @return multitype:|Ambigous <multitype:, multitype:unknown >
	 */
	public function getData($table, $all=false, $select=array(), $where=array(), $order=array(), $limit=array()){
		$ret = array();
		if(empty($table)){
			return $ret;
		}
		$selectWord = $select ? implode(',', $select) : '*';
		$whereWord = $where ? " WHERE ". implode(' AND ', $where) : '';
		$orderWord = $order ? " ORDER BY ". implode(',', $order) : '';
		$limitWord = $limit ? " LIMIT ". implode(',', $limit) : '';
		
		$sql = "SELECT  {$selectWord} FROM {$table}  {$whereWord} {$orderWord} {$limitWord} ";
		if($all){
			return $this->getAll($sql, MYSQL_ASSOC);
		}else{
			return $this->getOne($sql, MYSQL_ASSOC);
		}
	}
	
}	// END CLASS