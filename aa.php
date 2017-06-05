<?php


		echo "<pre>";


		include('./demo/db_example.php');	
		$dataNum =  $database->count('menu');

		$list = array();
  		do{
  		
	  		$num  = rand(1, $dataNum);
	  		$list =  $database->select('menu',"cname", array("id[=]" => $num ));

  		}while( empty($list[0]) );

  		var_dump( $list[0] );