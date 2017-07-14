<?php


/*
    这是一个底层数据工具类,这里包含各种数据需求!
*/

class restDataController
{

    public $link  ;

    public function __construct () 
    {
            $this->link = new medoo([
                // 必须配置项
                'database_type' => 'mysql',
                'database_name' => 'cuirine',
                'server' => 'localhost',
                'username' => 'root',
                'password' => '22113344@@aa',
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

            // $this->redis =  new  Redis;
            // $this->redis->connect('localhost',6379);
    }


    //搜索指定范围的店铺
    public function searchRest ($x, $y) 
    {

        $range = 1000;

        $whereArr =  ['and' => ['x[<>]' => [($x-$range), ($x+$range)], 'y[<>]' => [($y-$range), ($y+$range)]]];
      
        $fielt =  array('id', 'res_name');

        $arr = $this->link->select('res_address', $fielt, $whereArr);
      
        return $arr;
    }   


    //查询用户附件店铺
    public function getUserPosiRest  ($uid) 
    {

        $fielt = array('x', 'y');

        $whereArr = array('user_id[=]' => $uid);

        $arr = $this->link->select('user_info', $fielt, $whereArr);

        if (!$arr) { return false; }

        $arr  = $arr[0];

        $arr = $this->searchRest($arr['x'], $arr['y']);

        return $arr ;

    }


    //查询用户地址
    public function getUserLoaction ($uid) 
    {

        $fielt = array('address');

        $whereArr = array('user_id[=]' => $uid);

        $arr = $this->link->select('user_info', $fielt, $whereArr);

        return $arr[0];
    }

    /*
        根据ip获取店铺菜单(店铺ID)
        返回菜单数组
    */
    public function getRestMenu ($id)
    {

        $fielt = array('menu_name', 'menu_price', 'menu_theme');

        $whereArr = array('res_id[=]' => $id);

        $arr = $this->link->select('res_info',  $fielt, $whereArr);

        $arr = $arr[0];

        //判断主题菜单有没有值,如果有则查询拼接
        if ($arr['menu_theme']) {

            $fielt = array('name', 'price');

            $whereArr = array('thme_id[=]' => $arr['menu_theme']);

            $arr2 = $this->link->select('menu_theme',  $fielt, $whereArr);

            $arr2 =  $arr2[0];

            $arr['menu_name'] =  $arr['menu_name'].','.$arr2['name'] ;
            $arr['menu_price'] = $arr['menu_price'].','.$arr2['price'] ;
        
        }   
       return $arr;
    }

    //随机获取菜单
    public function getMenu ()
    {

        $whereArr = array('user_id[=]' => $GLOBALS['userID']);

        $sel = $this->link->select('user_info', 'user_sel', $whereArr);

        if ( empty($sel) ) {

            return $arr = $this->getSelMenu();  
            var_dump($arrs);

        } else {

            return $arr =  $this->getAllMenu();
            var_dump($arr);
        }

        
    } 


    //在所有菜名中随机抽取
    public function getAllMenu () 
    {   
        $num  = $this->link->count('cuisine_name');
     
        $rand = rand(1, $num);

        // $whereArr = array('id' => $rand, 'limit' => array(20, ));

        $arr = $this->link->select('cuisine_name', 'name', array('id' => $rand));

        return $arr ;
    }

    //获取喜好菜单
    public function getSelMenu () 
    {
        //查询用户喜好
        $data = $this->link->select('user_info', 'user_sel', array('user_id[=]' =>  $GLOBALS['userID'] ));
        //切割拼接
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
                case 1 : $arr['cxid[=]'] = $v ;break;
                case 2 : $arr['kwid[=]'] = $v ;break;
                case 3 : $arr['scid[=]'] = $v ;break;
                case 5 : $arr['djid[=]'] = $v ;break;
            }   
        }
    
        //拼接用户查询条件
        $whereArr = [ 'and' => $arr ];

        //查询数据库
        $MenuNameData =  $this->link->select('cuisine_name', 'name', $whereArr);   //查询数据条数 
        var_dump($MenuNameData);  
        //返回查询数据
        return $MenuNameData ; 
    }

    //写入数据到redis
    // public function writeDataRedis () 
    // {

    //     //获取根据用户喜好获取菜单
    //     $NameData = $this->getMenuData();
    //     //写入redis
    //     foreach ( $NameData as $v ) {

    //         $this->redis->sadd($this->userID,$v);

    //     }   
    // }

    /*
        写入店铺信息(店铺信息)
    */
    // public function setRestInfo ($arr) 
    // {

    // }

    //写入用户爱好,出入字段,根据类表,自动抽取字串中关键之
    public function setUserSel ($comtent) 
    {
        $comtent = "-".$comtent;
        //获取父ID数组
        $fid =  $this->link->select('typec', 'id', array('pid[=]' => 0));   //查询数据条数
     
        $selArr = [];
        $nameSel = []; 
        //循环父id
        foreach($fid as $id)
        {
            //获取子ID数组
            $idArr =  $this->link->select('typec', '*', array('pid[=]' => $id)); 
            //循环子ID
            foreach($idArr as $v)
            {   
                //判断字符串是否包含
                if( strpos($comtent, $v['type_name']) ){
                    $selArr[]  = $v['pid'] . ':' . $v['id'] ;
                    $nameSel[] = $v['type_name'];
                }
            }       
        }

        $nameStr = implode($nameSel, ',');     //拼接条件用于返回
        //拼接用户查询条件
        $str = implode($selArr, ',');

        $data = array(
            'user_sel' => $str
        );

        $this->wirteUInfo($data, 'user_info', 'user_id', $GLOBALS['userID']);

        return $nameStr ;
    }



    //获取用户在redis中剩余的条数
    public function getNum () 
    {   
        return $this->redis->scard($this->userID);
    }


    //错误信息返回
    public function error () 
    {

        $erro = $this->link->log() ;

        file_put_contents('writeDataLog.txt', $erro);

        return  $this->link->log();
        
    }


    /*
        先检查用户有没有数据,有则更新,无则创建
        插入用户数据(数据, 表名, 约束字段, id),
        注意:在函数声明的时候要写入用户ID
    */
    public function wirteUInfo ($data, $tableName, $openid, $userID) 
    {
        $userid = $this->link->select($tableName, 'user_id', array($openid.'[=]' => $userID ));

        if (empty($userid)) {

            return $this->link->insert($tableName, $data);

        } else {

            return $this->link->update($tableName, $data, array($openid.'[=]' => $userID ));
        }
    }



}


// $GLOBALS['userID'] =  'gh_8975a14402a2';
//   echo "<pre>";
//     $aa =  new restDataController();

//     $data = $aa->getMenu();

//     echo "<pre>" ;

//     var_dump($data);