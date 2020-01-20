<?php
class Gdata{

	public static $result;

	//私有属性，用于保存实例
    private static $link;

    private static $db;

    //公有属性，用于测试
    public $a;

    //公有方法，用于获取实例
    public static function getInstance($host , $user , $pwd , $charset , $name){
        //判断实例有无创建，没有的话创建实例并返回，有的话直接返回
        if(empty(self::$link)){
            self::$link = new self($host , $user , $pwd , $charset , $name);
        }
        return self::$link;
    }

    //克隆方法私有化，防止复制实例
    private function __clone(){}

	public function get_data()
	{
		if(empty(self::$result)){
			self::$result = $_SESSION['data_user'];
			return self::$result;
		}
		return self::$result;
	}

	public function __construct($host , $user , $pwd , $charset , $name)
	{
		try {
		    $link = new PDO("mysql:host={$host};dbname={$name}", $user, $pwd);
		    self::$db = $link;
	   	}catch (PDOException $e) {
		    print "Error!: " . $e->getMessage() . "<br/>";
		    die();
		}
		return self::$db;
	}

	public function getrandImg()
	{	
		@session_start();
		$sql = "SELECT id,openid,nickname,avatar,mobile FROM ims_ewei_shop_member as code WHERE id >= ((SELECT MAX(id) FROM ims_ewei_shop_member )-(SELECT MIN(id) FROM ims_ewei_shop_member )) * RAND() + (SELECT MIN(id) FROM ims_ewei_shop_member ) LIMIT 1";
		$db = self::$db;
		$stmt = $db->query($sql);
		$data = $stmt->fetchAll(PDO::FETCH_CLASS);
		if(isset($_SESSION['data_user']))
		{
		    $_SESSION['data_user'][] = $data;
		}
		else
		{
		    $_SESSION['data_user'][] = $data;
		}
		
		return json_encode(['error'=>0,'data'=>$data[0],'result'=>$this->get_data()]);
	}

	public function getrand()
	{	
		$sql = "SELECT * FROM ims_session_data as code WHERE id >= ((SELECT MAX(id) FROM ims_session_data )-(SELECT MIN(id) FROM ims_session_data )) * RAND() + (SELECT MIN(id) FROM ims_session_data ) LIMIT 1";
		$stmt = (self::$db)->prepare($sql);
		
		$stmt->execute(array(':login'=>'kevin2',':password'=>''));

		echo $dbh->lastinsertid(); 
	}
}
$gdata = Gdata::getInstance('127.0.0.1','root','root','utf8','yazhoubenteng');
function open($save_path, $session_name){
	global $sess_save_path;
	$sess_save_path = $save_path; 
	return(true);
}

//关闭的时候调用
function close(){
 	return(true);
}

function read($id){ 
	global $sess_save_path; 
	$sess_file = "$sess_save_path/sess_$id"; 
	return (string) @file_get_contents($sess_file);
}

//脚本执行结束之前，执行写入操作
function write($id, $sess_data){ 
	echo "sdfsf"; 
	global $sess_save_path; 
	$sess_file = "$sess_save_path/sess_$id"; 
	if ($fp = @fopen($sess_file, "w")) {
	 $return = fwrite($fp, $sess_data);
	 fclose($fp); return $return; 
	} else { 
		return(false); 
	}
}

function destroy($id){ 
	global $sess_save_path; 
	$sess_file = "$sess_save_path/sess_$id"; 
	return(@unlink($sess_file));
}

function gc($maxlifetime){ 
	global $sess_save_path; 
	foreach (glob("$sess_save_path/sess_*") as $filename) { 
		if (filemtime($filename) + $maxlifetime < time()) { 
			@unlink($filename); 
		} 
	} 
	return true;
}

if(!empty($gdata)){
	echo $gdata->getrandImg();
}
?>