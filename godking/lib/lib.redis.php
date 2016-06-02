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
 * @date: 2016年5月30日
 * @version: v1.0.0
 */
defined ( 'IN_GAME' ) or die ( 'Comes Error!' );
class Lib_Redis {
	private $oRedis = null; // 连接对象
	private $persist = false; // 是否长连接(多线程版本的Redis不支持长连接)
	private $connect = false; // 是否连接上
	private $connected = false; // 是否已经连接过
	private $connectlast = 0; // 记录最后连接的时间.每隔一段时间强制连一次
	private $timeout = 3; // 连接超时.秒为单位
	public $aServer = array (); // 地址配置
	public $prefix = ''; // 所有Key前缀
	public $die = false; // 出错后是否完全退出脚本
	public $seria = false; // 是否进行序列化
	public $count = 0; // 连接次数
	/**
	 *  构造函数
	 * @param unknown $aServer
	 * @param string $persist
	 */
	public function __construct($aServer, $persist = false) {
		$this->aServer = $aServer;
		$this->persist = $persist;
		
		if (! class_exists ( 'Redis' )) { // 强制使用
			die ( 'This Lib Requires The Redis Extention!' );
		}
	}
	
	/**
	 * 连接.每个实例仅连接一次
	 *
	 * @return Boolean
	 */
	private function connect() {
		$this->count ++; // 统计次数
		if ((! $this->connected) || (time () - $this->connectlast > 30)) { // 没有连接过或者链接超过了10秒
			$this->connected = true; // 标志已经连接过一次
			$this->connectlast = time (); // 记录此次连接的时间
			try {
				$this->oRedis = new Redis ();
				if (empty ( $this->aServer [1] )) { // 通过套接字连接
					$this->connect = $this->persist ? $this->oRedis->pconnect ( $this->aServer [0] ) : $this->oRedis->connect ( $this->aServer [0] );
				} else {
					$this->connect = $this->persist ? $this->oRedis->pconnect ( $this->aServer [0], $this->aServer [1], ( float ) $this->timeout ) : $this->oRedis->connect ( $this->aServer [0], $this->aServer [1], ( float ) $this->timeout );
				}
				// $this->oRedis->setOption(Redis::OPT_PREFIX, $this->prefix); //Key前缀
				$this->oRedis->setOption ( Redis::OPT_SERIALIZER, $this->seria ? Redis::SERIALIZER_PHP : Redis::SERIALIZER_NONE ); // Redis::SERIALIZER_IGBINARY二进制序列化
			} catch ( RedisException $e ) { // 连接失败,记录
				$this->errorlog ( 'Connect', $e->getCode (), $e->getMessage (), false );
			}
		}
		return $this->connect ? true : false;
	}
	
	/**
	 * 设置.有则覆盖.true成功false失败
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @return Boolean
	 */
	public function set($key, $value) {
		return $this->connect () && $this->oRedis->set ( $key, $value );
	}
	/**
	 * 设置带过期时间的值
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @param int $expire
	 *        	过期时间.默认24小时
	 * @return Boolean
	 */
	public function setex($key, $value, $expire = 86400) {
		return $this->connect () ? $this->oRedis->setex ( $key, $expire, $value ) : false;
	}
	/**
	 * 添加.存在该Key则返回false.
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @return Boolean
	 */
	public function setnx($key, $value) {
		return $this->connect () ? $this->oRedis->setnx ( $key, $value ) : false;
	}
	/**
	 * 原子递增1.不存在该key则基数为0.注意因为serialize的关系不能在set方法的key上再执行此方法
	 *
	 * @param String $key        	
	 * @return false/int 返回最新的值
	 */
	public function incr($key) {
		return $this->connect () ? $this->oRedis->incr ( $key ) : false;
	}
	/**
	 * 原子递加指定的数.不存在该key则基数为0,注意$value可以为负数.返回的结果也可能是负数
	 *
	 * @param String $key        	
	 * @param int $value        	
	 * @return false/int 返回最新的值
	 */
	public function incrBy($key, $value) {
		return $this->connect () ? $this->oRedis->incrBy ( $key, ( int ) $value ) : false;
	}
	/**
	 * 原子递减1.不存在该key则基数为0.可以减成负数
	 *
	 * @param String $key        	
	 * @return false/int 返回最新的值
	 */
	public function decr($key) {
		return $this->connect () ? $this->oRedis->decr ( $key ) : false;
	}
	/**
	 * 原子递减指定的数.不存在该key则基数为0,注意$value可以是负数(负负得正就成递增了).可以减成负数
	 *
	 * @param String $key        	
	 * @param int $value        	
	 * @return false/int 返回最新的值
	 */
	public function decrBy($key, $value) {
		return $this->connect () ? $this->oRedis->decrBy ( $key, ( int ) $value ) : false;
	}
	/**
	 * 获取.不存在则返回false
	 *
	 * @param String $key        	
	 * @return false/Mixed
	 */
	public function get($key) {
		return $this->connect () ? $this->oRedis->get ( $key ) : false;
	}
	/**
	 * 先获取该key的值,然后以新值替换掉该key.该key不存在则添加同时返回false
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @return Mixed/false
	 */
	public function getSet($key, $value) {
		return $this->connect () ? $this->oRedis->getSet ( $key, $value ) : false;
	}
	/**
	 * 从存储器中随机获取一个key
	 *
	 * @return String
	 */
	public function randomKey() {
		return $this->connect () ? $this->oRedis->randomKey () : '';
	}
	/**
	 * 选择数据库
	 *
	 * @param int $dbindex
	 *        	0-16(根据配置文件中的database)
	 * @return Boolean成功或者库不存在
	 */
	public function select($dbindex) {
		return $this->connect () ? $this->oRedis->select ( $dbindex ) : false;
	}
	/**
	 * 把某个key转移到另一个db中
	 *
	 * @param String $key        	
	 * @param int $dbindex
	 *        	0-...
	 * @return Boolean 当前db中没有该key或者...
	 */
	public function move($key, $dbindex) {
		return $this->connect () ? $this->oRedis->move ( $key, $dbindex ) : false;
	}
	/**
	 * 重命名某个Key.注意如果目的key存在将会被覆盖
	 *
	 * @param String $srcKey        	
	 * @param String $dstKey        	
	 * @return Boolean 源key和目的key相同或者源key不存在...
	 */
	public function renameKey($srcKey, $dstKey) {
		return $this->connect () ? $this->oRedis->renameKey ( $srcKey, $dstKey ) : false;
	}
	/**
	 * 重命名某个Key.和renameKey不同: 如果目的key存在将不执行
	 *
	 * @param String $srcKey        	
	 * @param String $dstKey        	
	 * @return Boolean 源key和目的key相同或者源key不存在或者目的key存在
	 */
	public function renameNx($srcKey, $dstKey) {
		return $this->connect () ? $this->oRedis->renameNx ( $srcKey, $dstKey ) : false;
	}
	/**
	 * 设置某个key过期时间(Time To Live)expire.只能设置一次
	 *
	 * @param String $key        	
	 * @param int $ttl
	 *        	存活时长(秒)
	 * @return Boolean
	 */
	public function setTimeout($key, $ttl) {
		return $this->connect () ? $this->oRedis->setTimeout ( $key, $ttl ) : false;
	}
	/**
	 * 设置某个key在特定的时间过期
	 *
	 * @param String $key        	
	 * @param int $timestamp
	 *        	时间戳
	 * @return Boolean
	 */
	public function expireAt($key, $timestamp) {
		return $this->connect () ? $this->oRedis->expireAt ( $key, $timestamp ) : false;
	}
	/**
	 * 获取对$key的描述.STRING for "encoding", LONG for "refcount" and "idletime", FALSE if the key doesn't exist
	 *
	 * @param String $retrieve        	
	 */
	public function object($retrieve, $key) {
		return $this->connect () ? $this->oRedis->object ( $retrieve, $key ) : false;
	}
	/**
	 * 批量获取.注意: 如果某键不存在则对应的值为false
	 *
	 * @param Array $keys        	
	 * @return Array 原顺序返回
	 */
	public function getMultiple($keys) {
		return $this->connect () && is_array ( $keys ) && count ( $keys ) ? $this->oRedis->getMultiple ( $keys ) : array ();
	}
	/**
	 * List章节 无索引序列 把元素加入到队列左边(头部).如果不存在则创建一个队列.返回该队列当前元素个数/false
	 * 注意对值的匹配要考虑到serialize.array(1,2)和array(2,1)是不同的值
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @return false/Int. 如果连接不上或者该key已经存在且不是一个队列
	 */
	public function lPush($key, $value) {
		return $this->connect () ? $this->oRedis->lPush ( $key, $value ) : false;
	}
	/**
	 * 往一个已存在的队列左边加元素.返回0(如果队列不存在)或最新的元素个数
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @return false/Int. 如果连接不上或者该key不存在或者不是一个队列
	 */
	public function lPushx($key, $value) {
		return $this->connect () ? $this->oRedis->lPushx ( $key, $value ) : false;
	}
	/**
	 * 把元素加入到队列右边(尾部).如果不存在则创建一个队列.返回该队列当前元素个数/false
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @return false/int 如果连接不上或者该key已经存在且不是一个队列
	 */
	public function rPush($key, $value) {
		return $this->connect () ? $this->oRedis->rPush ( $key, $value ) : false;
	}
	/**
	 * 往一个已存在的队列右边加元素.返回0(如果队列不存在)或最新的元素个数
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @return false/Int. 如果连接不上或者该key不存在或者不是一个队列
	 */
	public function rPushx($key, $value) {
		return $this->connect () ? $this->oRedis->rPushx ( $key, $value ) : false;
	}
	/**
	 * 弹出(返回并清除)队列头部(最左边)元素
	 *
	 * @param String $key        	
	 * @return Mixed/false
	 */
	public function lPop($key) {
		return $this->connect () ? $this->oRedis->lPop ( $key ) : false;
	}
	/**
	 * 弹出队列尾部(最右边)元素
	 *
	 * @param String $key        	
	 * @return Mixed/false
	 */
	public function rPop($key) {
		return $this->connect () ? $this->oRedis->rPop ( $key ) : false;
	}
	/**
	 * 情况形如lPop方法.只要其中一个列表存在且有值则立即返回.否则等待对应的秒数直到有相应的列表加入为止(慎用)
	 * 大致用途就是:监听N个列表,只要其中有一个列表有数据就立即返回该列表左边的数据
	 *
	 * @param String/Array $keys        	
	 * @param int $timeout        	
	 * @return Array array('列表键名', '列表最左边的值')
	 */
	public function blPop($keys, $timeout) {
		if (! $this->connect ()) {
			return array ();
		}
		try {
			$value = $this->oRedis->blPop ( $keys, $timeout );
		} catch ( RedisException $e ) {
			$value = array ();
		}
		return is_array ( $value ) ? $value : array ();
	}
	/**
	 * 情况形如rPop方法.这里指定一个延时只要其中一个列表存在且有值则立即返回.否则等待对应的秒数直到有相应的列表加入为止(慎用)
	 * 参考:blPop
	 *
	 * @param String/Array $keys        	
	 * @param int $timeout        	
	 * @return Array array('列表键名', '列表最右边的值')
	 */
	public function brPop($keys, $timeout) {
		if (! $this->connect ()) {
			return array ();
		}
		try {
			$value = $this->oRedis->brPop ( $keys, $timeout );
		} catch ( RedisException $e ) {
			$value = array ();
		}
		return is_array ( $value ) ? $value : array ();
	}
	
	/**
	 * 返回队列里的元素个数.不存在则为0.不是队列则为false
	 *
	 * @param String $key        	
	 * @return int/false
	 */
	public function lSize($key) {
		return $this->connect () ? $this->oRedis->lSize ( $key ) : false;
	}
	/**
	 * 控制队列只保存某部分listTrim,即:删除队列的其余部分
	 *
	 * @param String $key        	
	 * @param int $start        	
	 * @param int $end        	
	 * @return Boolean 不是一个队列或者不存在...
	 */
	public function lTrim($key, $start, $end) {
		return $this->connect () && $this->oRedis->lTrim ( $key, $start, $end );
	}
	/**
	 * 获取队列的某个元素
	 *
	 * @param String $key        	
	 * @param int $index
	 *        	0第一个1第二个...-1最后一个-2倒数第二个
	 * @return Mixed/false 没有则为空字符串或者false
	 */
	public function lGet($key, $index) {
		return $this->connect () ? $this->oRedis->lGet ( $key, $index ) : false;
	}
	/**
	 * 修改队列中指定$index的元素
	 *
	 * @param String $key        	
	 * @param int $index        	
	 * @param Mixed $value        	
	 * @return Boolean 该$index不存在或者该key不是一个队列为false
	 */
	public function lSet($key, $index, $value) {
		return $this->connect () && $this->oRedis->lSet ( $key, $index, $value );
	}
	/**
	 * 取出队列的某一段.不存在则返回空数组
	 *
	 * @param String $key        	
	 * @param String $start
	 *        	相当于$index:第一个为0...最后一个为-1
	 * @param String $end        	
	 * @return Array
	 */
	public function lGetRange($key, $start, $end) {
		return $this->connect () ? $this->oRedis->lGetRange ( $key, $start, $end ) : array ();
	}
	/**
	 * 删掉队列中的某些值
	 *
	 * @param String $key        	
	 * @param Mixed $value
	 *        	要删除的值.可以是复杂数据,但要考虑serialize
	 * @param int $count
	 *        	去掉的个数,>0从左到右去除;0为去掉所有;<0从右到左去除
	 * @return Boolean/int 删掉的值
	 */
	public function lRemove($key, $value, $count = 0) {
		return $this->connect () ? $this->oRedis->lRemove ( $key, $value, $count ) : false;
	}
	/**
	 * 在队列的某个特定值前/后面插入元素(如果有多个相同特定值则确定为左边起第一个)
	 *
	 * @param String $key        	
	 * @param int $direct
	 *        	0往后面插入1往前面插入
	 * @param Mixed $pivot        	
	 * @param Mixed $value        	
	 * @return false/int 列表当前元素个数或者-1表示元素不存在或不是列表
	 */
	public function lInsert($key, $direct, $pivot, $value) {
		return $this->connect () ? $this->oRedis->lInsert ( $key, $direct ? Redis::BEFORE : Redis::AFTER, $pivot, $value ) : false;
	}
	/**
	 * 给该key添加一个唯一值.相当于制作一个没有重复值的数组
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @return false/int 该值存在或者该键不是一个集合返回0,连接失败为false,否则为添加成功的个数1
	 */
	public function sAdd($key, $value) {
		return $this->connect () ? $this->oRedis->sAdd ( $key, $value ) : false;
	}
	/**
	 * 获取某key对象个数
	 *
	 * @param String $key        	
	 * @return int 不存在则为0
	 */
	public function sSize($key) {
		return $this->connect () ? $this->oRedis->sSize ( $key ) : 0;
	}
	/**
	 * 随机弹出一个值.
	 *
	 * @param String $key        	
	 * @return Mixed/false 没有值了或者不是一个集合
	 */
	public function sPop($key) {
		return $this->connect () ? $this->oRedis->sPop ( $key ) : false;
	}
	/**
	 * 随机取出一个值.与sPop不同,它不删除值
	 *
	 * @param String $key        	
	 * @return Mixed/false
	 */
	public function sRandMember($key) {
		return $this->connect () ? $this->oRedis->sRandMember ( $key ) : false;
	}
	/**
	 * 返回所给key列表都有的那些值,相当于求交集
	 * $keys Array
	 *
	 * @return Array 如果某集合不存在或者某键非集合返回空数组
	 */
	public function sInter($keys) {
		return $this->connect () && is_array ( $result = $this->oRedis->sInter ( $keys ) ) ? $result : array ();
	}
	/**
	 * 把所给$keys列表都有的那些值存到$key指定的数组中.相当于执行sInter操作然后再存到另一个数组中
	 * $key String 要存到的数组key 注意该数组如果存在会被覆盖
	 * $keys Array
	 *
	 * @return false/int 新集合的元素个数或者某key不存在为false
	 */
	public function sInterStore($key, $keys) {
		return $this->connect () ? call_user_func_array ( array (
				$this->oRedis,
				'sInterStore' 
		), array_merge ( array (
				$key 
		), $keys ) ) : 0;
	}
	/**
	 * 返回所给key列表所有的值,相当于求并集
	 *
	 * @param Array $keys        	
	 * @return Array
	 */
	public function sUnion($keys) {
		return $this->connect () && is_array ( $result = $this->oRedis->sUnion ( $keys ) ) ? $result : array ();
	}
	/**
	 * 把所给key列表所有的值存储到另一个数组
	 *
	 * @param String $key        	
	 * @param Array $keys        	
	 * @return int/false 并集(新集合)的数量
	 */
	public function sUnionStore($key, $keys) {
		return $this->connect () ? call_user_func_array ( array (
				$this->oRedis,
				'sUnionStore' 
		), array_merge ( array (
				$key 
		), ( array ) $keys ) ) : 0;
	}
	/**
	 * 返回所给key列表想减后的集合,相当于求差集
	 *
	 * @param Array $keys
	 *        	注意顺序,前面的减后面的
	 * @return Array
	 */
	public function sDiff($keys) {
		return $this->connect () && is_array ( $result = $this->oRedis->sDiff ( $keys ) ) ? $result : array ();
	}
	/**
	 * 把所给key列表差集存储到另一个数组
	 *
	 * @param String $key
	 *        	要存储的目的数组
	 * @param Array $keys        	
	 * @return int/false 差集的数量
	 */
	public function sDiffStore($key, $keys) {
		return $this->connect () ? call_user_func_array ( array (
				$this->oRedis,
				'sDiffStore' 
		), array_merge ( array (
				$key 
		), ( array ) $keys ) ) : 0;
	}
	/**
	 * 删除该集合中对应的值
	 *
	 * @param String $key        	
	 * @param String $value        	
	 * @return Boolean 没有该值返回false
	 */
	public function sRemove($key, $value) {
		return $this->connect () && $this->oRedis->sRemove ( $key, $value );
	}
	/**
	 * 把某个值从一个key转移到另一个key
	 *
	 * @param String $srcKey        	
	 * @param String $dstKey        	
	 * @param Mixed $value        	
	 * @return Boolean 源key不存在/目的key不存在/源值不存在->false
	 */
	public function sMove($srcKey, $dstKey, $value) {
		return $this->connect () && $this->oRedis->sMove ( $srcKey, $dstKey, $value );
	}
	/**
	 * 判断该数组中是否有对应的值
	 *
	 * @param String $key        	
	 * @param String $value        	
	 * @return Boolean 集合不存在或者值不存在->false
	 */
	public function sContains($key, $value) {
		return $this->connect () && $this->oRedis->sContains ( $key, $value );
	}
	/**
	 * 获取某数组所有值sGetMembers
	 *
	 * @param String $key        	
	 * @return Array 顺序是不固定的
	 */
	public function sMembers($key) {
		return $this->connect () && is_array ( $result = $this->oRedis->sMembers ( $key ) ) ? $result : array ();
	}
	/**
	 * 有序集合.添加一个指定了索引值的元素(默认索引值为0).元素在集合中存在则更新对应$score
	 *
	 * @param String $key        	
	 * @param int $score
	 *        	索引值
	 * @param Mixed $value
	 *        	注意考虑到默认使用了序列化,此处最好强制数据类型
	 * @return false/int 成功加入的个数
	 */
	public function zAdd($key, $score, $value) {
		return $this->connect () ? $this->oRedis->zAdd ( $key, $score, $value ) : false;
	}
	/**
	 * 获取指定单元的数据
	 *
	 * @param String $key        	
	 * @param int $start
	 *        	起始位置,从0开始
	 * @param int $end
	 *        	结束位置,-1结束
	 * @param Boolean $withscores
	 *        	是否返回索引值.如果是则返回[值=>索引]的数组.如果要返回索引值,存入的时候$value必须是标量
	 * @return Array
	 */
	public function zRange($key, $start, $end, $withscores = false) {
		return $this->connect () && is_array ( $result = $this->oRedis->zRange ( $key, $start, $end, $withscores ) ) ? $result : array ();
	}
	/**
	 * 获取指定单元的反序排列的数据
	 *
	 * @param String $key        	
	 * @param int $start        	
	 * @param int $end        	
	 * @param Boolean $withscores
	 *        	是否返回索引值.如果是则返回值=>索引的数组
	 * @return Array
	 */
	public function zRevRange($key, $start, $end, $withscores = false) {
		return $this->connect () && is_array ( $result = $this->oRedis->zRevRange ( $key, $start, $end, $withscores ) ) ? $result : array ();
	}
	/**
	 * 获取指定条件下的集合
	 *
	 * @param String $key        	
	 * @param int $start
	 *        	最小索引值
	 * @param int $end
	 *        	最大索引值
	 * @param Array $options
	 *        	array('withscores'=>true,limit=>array($offset, $count))
	 * @return Array
	 */
	public function zRangeByScore($key, $start, $end, $options = array()) {
		return $this->connect () && is_array ( $result = $this->oRedis->zRangeByScore ( $key, $start, $end, $options ) ) ? $result : array ();
	}
	/**
	 * 获取指定条件下的反序排列集合
	 *
	 * @param String $key        	
	 * @param int $start
	 *        	最大索引值
	 * @param int $end
	 *        	最小索引值
	 * @param Array $options
	 *        	array('withscores'=>true,limit=>array($offset, $count))
	 * @return Array
	 */
	public function zRevRangeByScore($key, $start, $end, $options = array()) {
		return $this->connect () && is_array ( $result = $this->oRedis->zRevRangeByScore ( $key, $start, $end, $options ) ) ? $result : array ();
	}
	/**
	 * 返回指定索引值区域内的元素个数
	 *
	 * @param String $key        	
	 * @param int/String $start
	 *        	最小索引值 前面加左括号表示不包括本身如: '(3' 表示>3而不是默认的>=3
	 * @param int/String $end
	 *        	最大索引值 '(4'表示...
	 * @return int
	 */
	public function zCount($key, $start, $end) {
		return $this->connect () ? $this->oRedis->zCount ( $key, $start, $end ) : 0;
	}
	/**
	 * 删除指定索引值区域内的所有元素zRemRangeByScore
	 *
	 * @param String $key        	
	 * @param int $start
	 *        	最小索引值
	 * @param int $end
	 *        	最大索引值
	 * @return int
	 */
	public function zDeleteRangeByScore($key, $start, $end) {
		return $this->connect () ? $this->oRedis->zDeleteRangeByScore ( $key, $start, $end ) : 0;
	}
	/**
	 * 删除指定排序范围内的所有元素
	 *
	 * @param int $start
	 *        	排序起始值
	 * @param int $end        	
	 * @return int
	 */
	public function zDeleteRangeByRank($key, $start, $end) {
		return $this->connect () ? $this->oRedis->zDeleteRangeByRank ( $key, $start, $end ) : 0;
	}
	/**
	 * 获取集合元素个数zCard
	 *
	 * @param String $key        	
	 * @return int
	 */
	public function zSize($key) {
		return $this->connect () ? $this->oRedis->zSize ( $key ) : 0;
	}
	/**
	 * 获取某集合中某元素的索引值
	 *
	 * @param String $key        	
	 * @param String $member        	
	 * @return int/false 没有该值为false
	 */
	public function zScore($key, $member) {
		return $this->connect () ? $this->oRedis->zScore ( $key, $member ) : 0;
	}
	/**
	 * 获取指定元素的排序值
	 *
	 * @param String $key        	
	 * @param String $member        	
	 * @return int/false 不存在为false
	 */
	public function zRank($key, $member) {
		return $this->connect () ? $this->oRedis->zRank ( $key, $member ) : 0;
	}
	/**
	 * 获取指定元素的反向排序值
	 *
	 * @param String $key        	
	 * @param String $member        	
	 * @return int/false 不存在为false
	 */
	public function zRevRank($key, $member) {
		return $this->connect () ? $this->oRedis->zRevRank ( $key, $member ) : 0;
	}
	/**
	 * 给指定的元素累加索引值.元素不存在则会被添加
	 *
	 * @param String $key        	
	 * @param int $value
	 *        	要加的索引值量
	 * @param String $member        	
	 * @return int 该元素最新的索引值
	 */
	public function zIncrBy($key, $value, $member) {
		return $this->connect () ? $this->oRedis->zIncrBy ( $key, $value, $member ) : 0;
	}
	/**
	 * 得到一个并集存储到新的集合中
	 *
	 * @param String $keyOutput
	 *        	新集合名
	 * @param Array $arrayZSetKeys
	 *        	需要合并的集合 array('key1', 'key2')
	 * @param Array $arrayWeights
	 *        	对应集合中索引值要放大的倍数 array(5, 2)表示第一个集合中的索引值*5,第二个集合中的索引值*2,然后再合并
	 * @param String $aggregateFunction
	 *        	如果有相同元素,则取索引值的方法: "SUM", "MIN", "MAX"
	 * @return int 新集合的元素个数
	 */
	public function zUnion($keyOutput, $arrayZSetKeys, $arrayWeights, $aggregateFunction) {
		return $this->connect () ? $this->oRedis->zUnion ( $keyOutput, $arrayZSetKeys, $arrayWeights, $aggregateFunction ) : 0;
	}
	/**
	 * 得到一个交集存储到新的集合中
	 *
	 * @param String $keyOutput
	 *        	新集合名
	 * @param Array $arrayZSetKeys
	 *        	需要合并的集合 array('key1', 'key2')
	 * @param Array $arrayWeights
	 *        	对应集合中索引值要放大的倍数 array(5, 2)表示第一个集合中的索引值*5,第二个集合中的索引值*2,然后再合并
	 * @param String $aggregateFunction
	 *        	如果有相同元素,则取索引值的方法: "SUM", "MIN", "MAX"
	 * @return int 新集合的元素个数
	 */
	public function zInter($keyOutput, $arrayZSetKeys, $arrayWeights, $aggregateFunction) {
		return $this->connect () ? $this->oRedis->zInter ( $keyOutput, $arrayZSetKeys, $arrayWeights, $aggregateFunction ) : 0;
	}
	
	/**
	 * 设置或替换Hash.
	 *
	 * @param String $key        	
	 * @param String $hashKey        	
	 * @param Mixed $value        	
	 * @return Boolean
	 */
	public function hSet($key, $hashKey, $value) {
		return $this->connect () && in_array ( $this->oRedis->hSet ( $key, $hashKey, $value ), array (
				0,
				1 
		), true ) ? true : false; // 该处特殊.0为替换成功1为添加成功false为操作失败
	}
	/**
	 * 添加式
	 *
	 * @param String $key        	
	 * @param String $hashKey        	
	 * @param Mixed $value        	
	 * @return Boolean
	 */
	public function hSetNx($key, $hashKey, $value) {
		return $this->connect () && $this->oRedis->hSetNx ( $key, $hashKey, $value );
	}
	/**
	 * 获取单个.失败或不存在为false
	 *
	 * @param String $key        	
	 * @param String $hashKey        	
	 * @return Mixed
	 */
	public function hGet($key, $hashKey) {
		return $this->connect () ? $this->oRedis->hGet ( $key, $hashKey ) : false;
	}
	/**
	 * 该Key上Hash数量
	 *
	 * @param String $key        	
	 * @return int
	 */
	public function hLen($key) {
		return $this->connect () ? $this->oRedis->hLen ( $key ) : 0;
	}
	/**
	 * 删除.成功为true,否则false
	 *
	 * @param String $key        	
	 * @param String $hashKey        	
	 * @return Boolean
	 */
	public function hDel($key, $hashKey) {
		return $this->connect () && $this->oRedis->hDel ( $key, $hashKey );
	}
	/**
	 * 获取所有Key.不存在则为空数组
	 *
	 * @param String $key        	
	 * @return Array
	 */
	public function hKeys($key) {
		return $this->connect () && ($result = $this->oRedis->hKeys ( $key )) ? $result : array ();
	}
	/**
	 * 获取所有值.不存在则为空数组
	 *
	 * @param String $key        	
	 * @return Array
	 */
	public function hVals($key) {
		return $this->connect () && ($result = $this->oRedis->hVals ( $key )) ? $result : array ();
	}
	/**
	 * 获取所有键值对
	 *
	 * @param String $key        	
	 * @return Array
	 */
	public function hGetAll($key) {
		return $this->connect () && ($result = $this->oRedis->hGetAll ( $key )) ? $result : array ();
	}
	/**
	 * 判断$memberKey是否存在
	 *
	 * @param String $key        	
	 * @param String $memberKey        	
	 * @return Boolean
	 */
	public function hExists($key, $memberKey) {
		return $this->connect () && $this->oRedis->hExists ( $key, $memberKey );
	}
	/**
	 * 累加减操作.可以减为负数.如果初始值不是整型或者$value不是整型则为false
	 * 注意: 因为默认启用了序列化,只能通过此方法设置的$key上做此操作!!!
	 *
	 * @param String $key        	
	 * @param String $member        	
	 * @param int $value
	 *        	负数则为减
	 * @return int/false 最新的值
	 */
	public function hIncrBy($key, $member, $value) {
		return $this->connect () ? $this->oRedis->hIncrBy ( $key, $member, $value ) : false;
	}
	/**
	 * 批量获取.key不存在的对应的值为false
	 *
	 * @param String $key        	
	 * @param Array $memberKeys        	
	 * @return Array
	 */
	public function hMget($key, $memberKeys) {
		return $this->connect () && ($result = $this->oRedis->hMget ( $key, $memberKeys )) ? $result : array ();
	}
	/**
	 * 批量设置
	 *
	 * @param String $key        	
	 * @param Array $members
	 *        	键值对
	 * @return Boolean
	 */
	public function hMset($key, $members) {
		return $this->connect () && $this->oRedis->hMset ( $key, $members );
	}
	/**
	 * 往值后面追加字符串.不存在则创建
	 *
	 * @param String $key        	
	 * @param String $value        	
	 * @return int 最新值的长度
	 */
	public function append($key, $value) {
		return $this->connect () ? $this->oRedis->append ( $key, $value ) : 0;
	}
	/**
	 * 获取字符串的一部分.此方法仅针对append加的字符串有意义
	 *
	 * @param int $start        	
	 * @param int $end        	
	 * @return String 不存在则为''
	 */
	public function getRange($key, $start, $end) {
		return $this->connect () ? $this->oRedis->getRange ( $key, $start, $end ) : '';
	}
	/**
	 * 从$offset开始替换后面的字符串.$offset从0开始
	 *
	 * @param String $key        	
	 * @param int $offset        	
	 * @param String $value        	
	 * @return int 字符串最新的长度
	 */
	public function setRange($key, $offset, $value) {
		return $this->connect () ? $this->oRedis->setRange ( $key, $offset, $value ) : 0;
	}
	/**
	 * 返回值的长度
	 *
	 * @param String $key        	
	 * @return int 不存在为0
	 */
	public function strlen($key) {
		return $this->connect () ? $this->oRedis->strlen ( $key ) : 0;
	}
	/**
	 * 返回列表,集合,有序集合排序后的数据或者存储的元素个数
	 * $options = array('by' => 'some_pattern_*',
	 * 'limit' => array(0, 1),
	 * 'get' => 'some_other_pattern_*' or an array of patterns,
	 * 'sort' => 'asc' or 'desc',
	 * 'alpha' => true, //按字母排序
	 * 'store' => 'external-key')
	 *
	 * @return Array/int
	 */
	public function sort($key, $options) {
		return $this->connect () ? $this->oRedis->sort ( $key, $options ) : array ();
	}
	
	/**
	 * 移除某key的过期时间使得永不过期
	 *
	 * @return Boolean 没有设置过期时间或者没有该Key返回false
	 */
	public function persist($key) {
		return $this->connect () && $this->oRedis->persist ( $key );
	}
	/**
	 * 启动后台回写至硬盘
	 *
	 * @return Boolean
	 */
	public function bgrewriteaof() {
		return $this->connect () && $this->oRedis->bgrewriteaof ();
	}
	/**
	 * 转换从DB角色
	 *
	 * @param String $host
	 *        	从DB地址
	 * @param String $port
	 *        	从DB端口
	 * @return Boolean
	 */
	public function slaveof($host, $port) {
		return $this->connect () && $this->oRedis->slaveof ( $host, $port );
	}
	/**
	 * 开始一个事务处理
	 *
	 * @param int $mode
	 *        	事务类型1保证原子性2不保证
	 * @return muredis $ret = $redis->multi()
	 *         ->set('key1', 'val1')
	 *         ->get('key1')
	 *         ->set('key2', 'val2')
	 *         ->get('key2')
	 *         ->exec();
	 *         $ret == array(
	 *         0 => TRUE,
	 *         1 => 'val1',
	 *         2 => TRUE,
	 *         3 => 'val2');
	 *        
	 */
	public function multi($mode = 1) {
		return $this->connect () ? $this->oRedis->multi ( $mode == 1 ? Redis::MULTI : Redis::PIPELINE ) : $this;
	}
	/**
	 * 回滚事务
	 *
	 * @return Boolean
	 */
	public function discard() {
		return $this->connect () && $this->oRedis->discard ();
	}
	/**
	 * 提交事务
	 *
	 * @return Mixed 返回事务中各方法的返回值.如果采用了watch锁而值被改或者没有任何执行,则强制返回空数组
	 */
	public function exec() {
		return $this->connect () && is_array ( $result = $this->oRedis->exec () ) ? $result : array ();
	}
	/**
	 * 被动锁定某个/某些key.用于事务处理中:如果被锁定的key在提交事务前被改了则事务提交失败
	 *
	 * @return Boolean
	 */
	public function watch($keys) {
		return $this->connect () && $this->oRedis->watch ( $keys );
	}
	/**
	 * 解锁所有被锁key
	 *
	 * @return Boolean
	 */
	public function unwatch() {
		return $this->connect () && $this->oRedis->unwatch ();
	}
	/**
	 * 以下未确定
	 * public function publish(){
	 * }
	 * public function subscribe(){
	 * }
	 * public function unsubscribe(){
	 * }
	 * public function open(){
	 * }
	 * public function lLen(){
	 * }
	 * public function pipeline(){
	 * }
	 * public function brpoplpush(){
	 * }
	 * public function sortAsc(){
	 * }
	 * public function sortAscAlpha(){
	 * }
	 * public function sortDesc(){
	 * }
	 * public function sortDescAlpha(){
	 * }
	 */
	/**
	 * 获取对应值的某一位
	 *
	 * @param String $key        	
	 * @param int $offset
	 *        	要获取的位置(负数返回false)
	 * @return false/0/1 (不存在为0)
	 */
	public function getBit($key, $offset) {
		return $this->connect () ? $this->oRedis->getBit ( $key, $offset ) : false;
	}
	/**
	 * 设置对应值的某一位(位运算)
	 *
	 * @param String $key        	
	 * @param int $offset
	 *        	要修改的位置(负数则返回false)
	 * @param int $value
	 *        	要修改的值.只能是: false,true,0,1
	 * @return false/0/1 返回该位置修改前的值
	 */
	public function setBit($key, $offset, $value) {
		return $this->connect () ? $this->oRedis->setBit ( $key, $offset, $value ) : false;
	}
	/**
	 * 获取环境
	 *
	 * @param String $option        	
	 * @return Mixed
	 */
	public function getOption($option) {
		return $this->connect () ? $this->oRedis->getOption ( $option ) : '';
	}
	/**
	 * 设置配置.参看: Redis::...
	 *
	 * @param String $name        	
	 * @param String $value        	
	 * @return Boolean
	 */
	public function setOption($name, $value) {
		return $this->connect () && $this->oRedis->setOption ( $name, $value );
	}
	/**
	 * 删除对应的值zRem
	 *
	 * @param String $key        	
	 * @param Mixed $value        	
	 * @return Boolean/int 删除元素的个数(0/1)
	 */
	public function zDelete($key, $value) {
		return $this->connect () ? $this->oRedis->zDelete ( $key, $value ) : false;
	}
	/**
	 * 返回服务器信息
	 *
	 * @return Array
	 */
	public function info() {
		return $this->connect () ? $this->oRedis->info () : array ();
	}
	/**
	 * 重置统计信息
	 * Keyspace hits
	 * Keyspace misses
	 * Number of commands processed
	 * Number of connections received
	 * Number of expired keys
	 *
	 * @return Boolean
	 */
	public function resetStat() {
		return $this->connect () && $this->oRedis->resetStat ();
	}
	/**
	 * 返回某key剩余的时间.单位是秒
	 *
	 * @param String $key        	
	 * @return int/false -1为没有设置过期时间
	 */
	public function ttl($key) {
		return $this->connect () ? $this->oRedis->ttl ( $key ) : 0;
	}
	/**
	 * 批量设置
	 *
	 * @param Array $pairs
	 *        	索引数组,索引为key,值为...
	 * @return Boolean
	 */
	public function mset($pairs) {
		return $this->connect () && is_array ( $pairs ) && $this->oRedis->mset ( $pairs );
	}
	/**
	 * 批量添加.如果某key存在则为false并且其他key也不会被保存
	 *
	 * @param Array $pairs
	 *        	索引数组,索引为key,值为...
	 * @return Boolean
	 */
	public function msetnx($pairs) {
		return $this->connect () && is_array ( $pairs ) && $this->oRedis->msetnx ( $pairs );
	}
	/**
	 * 批量获取数据
	 *
	 * @param Array $pairs
	 *        	数组，其value为KEY组合
	 * @return Mixed 如果成功，返回与KEY对应位置的VALUE组成的数组
	 */
	public function mget($pairs) {
		return $this->connect () && is_array ( $pairs ) && is_array ( $result = $this->oRedis->mget ( $pairs ) ) ? $result : array ();
	}
	/**
	 * 从源队列尾部弹出一项加到目的队列头部.并且返回该项
	 *
	 * @param String $srcKey        	
	 * @param String $dstKey        	
	 * @return Mixed/false
	 */
	public function rpoplpush($srcKey, $dstKey) {
		return $this->connect () ? $this->oRedis->rpoplpush ( $srcKey, $dstKey ) : false;
	}
	/**
	 * 判断key是否存在
	 *
	 * @param String $key        	
	 * @return Boolean
	 */
	public function exists($key) {
		return $this->connect () && $this->oRedis->exists ( $key );
	}
	/**
	 * 获取符合匹配的key.仅支持正则中的*通配符.如->getKeys('*')
	 *
	 * @param String $pattern        	
	 * @return Array
	 */
	public function getKeys($pattern) {
		return $this->connect () && is_array ( $result = $this->oRedis->getKeys ( $pattern ) ) ? $result : array ();
	}
	/**
	 * 删除某key/某些key
	 *
	 * @param String/Array $keys        	
	 * @return int 被删的个数
	 */
	public function delete($keys) {
		return $this->connect () ? $this->oRedis->delete ( $keys ) : 0;
	}
	/**
	 * 返回当前key数量
	 *
	 * @return int
	 */
	public function dbSize() {
		return $this->connect () ? $this->oRedis->dbSize () : 0;
	}
	/**
	 * 密码验证.密码明文传输
	 *
	 * @param String $password        	
	 * @return Boolean
	 */
	public function auth($password) {
		return $this->connect () && $this->oRedis->auth ( $password );
	}
	/**
	 * 强制把内存中的数据写回硬盘
	 *
	 * @return Boolean 如果正在回写则返回false
	 */
	public function save() {
		return $this->connect () && $this->oRedis->save ();
	}
	/**
	 * 执行一个后台任务: 强制把内存中的数据写回硬盘
	 *
	 * @return Boolean 如果正在回写则返回false
	 */
	public function bgSave() {
		return $this->connect () && $this->oRedis->bgSave ();
	}
	/**
	 * 返回最后一次写回硬盘的时间
	 *
	 * @return int 时间戳
	 */
	public function lastSave() {
		return $this->connect () ? $this->oRedis->lastSave () : 0;
	}
	/**
	 * 返回某key的数据类型
	 *
	 * @param String $key        	
	 * @return int 存在于: REDIS_* 中
	 */
	public function type($key) {
		return $this->connect () ? $this->oRedis->type ( $key ) : Redis::REDIS_NOT_FOUND;
	}
	/**
	 * 清空当前数据库.谨慎执行
	 *
	 * @return int 1成功
	 */
	public function flushDB() {
		return $this->connect () && $this->oRedis->flushDB ();
	}
	/**
	 * 清空所有数据库.谨慎执行
	 *
	 * @return Boolean
	 */
	public function flushAll() {
		return $this->connect () && $this->oRedis->flushAll ();
	}
	/**
	 * 获取连接信息
	 *
	 * @return Boolean
	 */
	public function ping() {
		try {
			return $this->connect () && $this->oRedis->ping ();
		} catch ( RedisException $e ) {
			return false;
		}
	}
	/**
	 * 设置或获取配置参数.禁用!!!
	 *
	 * @param String $operation
	 *        	GET/SET
	 * @param String $key        	
	 * @param null/String $value        	
	 * @return Array/Boolean
	 */
	private function config($operation, $key, $value = null) {
		return $this->connect () ? $this->config ( $operation, $key, $value ) : false;
	}
	/**
	 * 关闭非持久连接
	 */
	public function close() {
		$this->connect && (($this->connected = false) || $this->oRedis->close ()); // 确保关闭
	}
	private function errorlog($keys, $code, $msg, $die = false) {
		$error = date ( 'H:i:s' ) . ":\n" . $code . ";\nkeys:" . var_export ( $keys, true ) . ";\nmsg:{$msg}\n";
		
		echo "<p>{$error}<p>";
		// oo::funModel ( 'logs' )->debug ( $error, 'muredis.txt' );
		($this->die || $die) && die ( 'Redis Invalid!!!' );
	}
}	// END CLASS