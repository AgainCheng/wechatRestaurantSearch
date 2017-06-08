<?php

include('./Model/Medoo/medoo.php');



/*

    这是一个底层数据工具类,这里包含各种数据需求!

*/

class restDataController
{
    public $link ; 



    //构造连接数据库
    public function __construct () 
    {
            $this->link = new medoo([
                // 必须配置项
                'database_type' => 'mysql',
                'database_name' => 'restaurant',
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

            // $this->userID = (string)$userID;

            // $this->redis =  new  Redis;
            // $this->redis->connect('localhost',6379);
    }




    //搜索指定范围的店铺
    public function searchRest ($x, $y) 
    {

        $range = 0.1;

        $whereArr =  ['and' => ['x[<>]' => [($x-$range), ($x+$range)], 'y[<>]' => [($y-$range), ($y+$range)]]];
      
        $fielt =  array('id', 'resname');

        $arr = $this->link->select('resBase', $fielt, $whereArr);

        return $arr;
    }   


    //查询用户附件店铺
    public function getUserPosiRest  ($uid) 
    {

        $fielt = array('x', 'y');

        $whereArr = array('userid[=]' => $uid);

        $arr = $this->link->select('ulocation', $fielt, $whereArr);

        if (!$arr) { return false; }

        $arr  = $arr[0];

        $arr = $this->searchRest($arr['x'], $arr['y']);

        return $arr ;

    }


    //查询用户地址
    public function getUserLoaction ($uid) 
    {

        $fielt = array('location');

        $whereArr = array('userid[=]' => $uid);

        $arr = $this->link->select('ulocation', $fielt, $whereArr);

        return $arr[0];
    }
    /*
        获取指定id店铺菜单(店铺ID)
        返回菜单
    */
    public function getRestMenu ($id)
    {

    }



    /*
        写入店铺信息(店铺信息)
    */
    public function setRestInfo ($arr) 
    {

    }



    /*
        先检查用户有没有数据,有则更新,无则创建
        插入用户数据(数据, 表名, 约束字段, id),
        注意:在函数声明的时候要写入用户ID
    */
    public function wirteUInfo ($data, $tableName, $openid, $userID) 
    {
        $userid = $this->link->select($tableName, 'id', array($openid.'[=]' => $userID ));

        if (empty($userid)) {

            return $this->link->insert($tableName, $data);

        } else {

            return $this->link->update($tableName, $data, array($openid.'[=]' => $userID ));
        }
    }



}

