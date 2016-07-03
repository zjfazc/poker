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
class Common_Function {
	static private $gObj = array();
	
	/**
	 * 单例
	 * @return Common_Function
	 */
	static function single(){
		$name = __FUNCTION__;
		if(!isset(self::$gObj[$name])){
			self::$gObj[$name] = new Common_Function();
		}
		return self::$gObj[$name];
	}
	
	/**
	 * 取正整数
	 *
	 * @param unknown $num        	
	 * @return mixed
	 */
	public function uint($num) {
		return max ( 0, ( int ) $num );
	}
	
	/**
	 * 安全性检测.调用escape存入的,一定要调unescape取出
	 * 
	 * @param unknown $string        	
	 * @return string
	 */
	public function escape($string) {
		return addslashes ( trim ( $string ) );
	}
	
	/**
	 * 安全性检测.调用escape存入的,一定要调unescape取出
	 * 
	 * @param unknown $string        	
	 * @return string
	 */
	public function unescape($string) {
		return stripslashes ( $string );
	}
	
	/**
	 * 去除系统添加的转义字符
	 * @param unknown $mixVar
	 * @return string|unknown
	 */
	public function magic_quote($mixVar) {
		if (get_magic_quotes_gpc ()) {
			if (is_array ( $mixVar )) {
				foreach ( $mixVar as $key => $value ) {
					$temp [$key] = $this->magic_quote ( $value );
				}
			} else {
				$temp = stripslashes ( $mixVar );
			}
			return $temp;
		} else {
			return $mixVar;
		}
	}
	
	/**
	 * api数据发送
	 * @param unknown $errorCode
	 * @param unknown $data
	 */
	public  function sendOut($errorCode, $data=array()){
		$etime = microtime(true);
		$aRet = array();
		$aRet['code'] = $errorCode[0];
		$aRet['codemsg'] = $errorCode[1];
		$aRet['exetime'] = $etime - SCRIPT_STIME; //脚本执行时间
		$aRet['data'] = $data;
		echo json_encode( $aRet);
	}
	
	/**
	 * 配置数据输出
	 * @param unknown $data
	 * @param unknown $filepath
	 * @param string $echo
	 */
	public function mkConfigOutput($data, $filepath, $echo=true){
		$cnt = @file_put_contents($filepath, $data);
		if($echo ){
			$txt = $cnt ? "成功" : "失败";
			echo "<br>{$txt}  output file : {$filepath}<br>";
		}
	}
	/**
	 *  设置在线版本配置
	 * @param unknown $name
	 * @param unknown $version
	 */
	public function setOnlineVersionControl($name, $version){
		$versions = Common_Gobal::dataFile('versionControl');
		$versions[$name] = $version;
		
		$outphp = "<?php \n";
		$outphp .= "// 在线配置版本控制 \n";
		$outphp .= "\$versionControl = array(); \n";
		
		foreach($versions as $key=>$val){
			$outphp .= "\$versionControl['{$key}'] = '{$val}'; \n";
		}
		$outphp .= "return \$versionControl; \n";
		
		$filepath = PATH_CONFIG . 'data' .DS . "data.versionControl.php";
		$this->mkConfigOutput($outphp, $filepath);
	}
}