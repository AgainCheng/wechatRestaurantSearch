<?php

    
	include('./vardor/carfan/Medoo/medoo.php');



class getMenuData 
{
    private  $link;



    /*
        连接数据库
    */
    public function __construct () 
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
    }


	 /*
     	随机返回数据库中的一条数据,菜名
    */
    public function getMenu() 
    {
        $dataNum =  $this->link->count('menu');   //查询数据条数
        $list = array();
        do{
            $num  = rand(1, $dataNum);
            $list =  $this->link->select('menu', "cname", array("id[=]" => $num ));

        }while( empty($list[0]) );
        return  $list[0];                       
    }

    /*
        根据用户id存储用户选项
        如果存在则更新
        如果不存在则添加

    */
    public function  addUserInfo ($uid, $arr) 
    {


        $this->link->insert('userStatus', $arr);



    }


    /*
        根据难度赛选赛选出数据
    */
    public function getMenuSle ($uid) 
    {

        $list = $this->link->select('menu', 'nd', array('userid' => $uid ));


        return $list[0];
    }



}