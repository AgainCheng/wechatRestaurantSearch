<?php

  	include('./vardor/carfan/Medoo/medoo.php');

class cuisineApp
{
	public $link ; 
	public $userID;
	public $redis;


	//构造连接数据库
    public function __construct ($userID) 
    {
            $this->link = new medoo([
                // 必须配置项
                'database_type' => 'mysql',
                'database_name' => 'cuisine',
                'server' => 'localhost',
                'username' => 'root',
                'password' => '123456',
                'charset' => 'utf8',
                // 可选参数
                'port' => 3306,
                // 可选，定义表的前缀
                'prefix' => '',
                // 连接参数扩展, 更多参考 http://www.php.net/manual/en/pdo.setattribute.php
                'option' => [
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]
            ]);

            $this->userID = $userID;

           	$this->redis =  new  Redis;
			$this->redis->connect('localhost',6379);
    }


	//设置用户喜好(用户id,字串)
	public function setUserSel ($uid,$comtent) 
	{
	
	    echo '<pre>';
	    //获取父ID数组
	    $fid =  $this->link->select('typec', 'id', array('fid[=]' => 0));   //查询数据条数

	  	$selArr = [];
	  	//循环父id
	    foreach($fid as $id)
	    {
	    	//获取子ID数组
	    	$idArr =  $database->select('typec', '*', array('fid[=]' => $id));  
	    	//循环子ID
	    	foreach($idArr as $v)
	    	{	
	    		//判断字符串是否包含
	    		if( strpos($comtent, $v['namec']) ){
	    			$selArr[] = $v['fid'] . ':' . $v['id'] ;
	    		}
	    	}   	
	    }

	    if( empty($selArr) ){
	    	return '客官抱歉,本店没有你要吃的类型,满足不了你的特殊爱好奥!!!';
	    }

	    // var_dump($selArr);

	    //拼接用户查询条件
		$str = implode($selArr, ',');

		$data = array(
			'id' => null,
			'userid' => $uid,
			'unsel' => $str
		);



		//写入数据数据库
		$userid = $database->select('userSel', 'id', array('userid[=]' => $uid ));

		if( empty($userid) ){

			$database->insert('userSel', $data);

		}else{

			$database->update('userSel', $data, array('userid[=]' => $uid ));

		}
	}



	//获取根据用户喜好获取菜单
	public function getMenuData () 
	{
		//查询用户喜好
		$data = $this->link->select('userSel', 'unsel', array('userid[=]' => $this->userID ));

		//拼接
		$fidArr  = explode(',', $data[0]);
		$selArr = [];
		foreach( $fidArr as $v ){
			$arr = explode(':', $v);
			$selArr[$arr[0]] = $arr[1];
		}


		//数据库字段定位,写死了,有待优化
		$arr = [] ;
		foreach($selArr as $k => $v){
			switch($k){
				case 1 : $arr['cid[=]'] = $v ;break;
				case 2 : $arr['kid[=]'] = $v ;break;
				case 3 : $arr['sid[=]'] = $v ;break;
				case 4 : $arr['did[=]'] = $v ;break;
			}	
		}
	 
		//拼接查询条件
		$whereArr = [ 'OR' => $arr ];

		//查询数据库
		$MenuNameData =  $this->link->select('menuc', 'NAME', $whereArr);   //查询数据条数	
		
		//返回查询数据
		return $MenuNameData ; 
	}


	//写入redis
	public function writeDataRedis () 
	{

		//获取根据用户喜好获取菜单
		$NameData = $this->getMenuData();
		//写入redis
		foreach( $NameData as $v ){

			$this->redis->sadd($this->userID,$v);

		}	
	}

	//随机返回redis中的菜名
	public function getMenu () 
	{



		if( empty($this->redis->scard($this->userID)) ){

			return false;
		}
		return $this->redis->spop($this->userID);
	}

	//获取用户在redis中剩余的条数
	public function getNum () 
	{	
		return $this->redis->scard($this->userID);
	}

}



	$comtent = "我要吃湘菜广东甜";
	$uid = '123123123';

	$aa =  new cuisineApp($uid);

	echo $aa->getMenu();
	echo "<br>";
	echo '客官你还剩'.$aa->getNum().'发奥!!!';