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
}
$gdata = Gdata::getInstance('127.0.0.1','root','root','utf8','yazhoubenteng');
if(!empty($gdata)){
	$gdata->getrandImg();
}
?>