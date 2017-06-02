<?php

echo '123';

		include('./demo/db_example.php');

		$arr = array(
			'id' => null,
			'uname'=>'cheng',
			'meg' => '接收到数据'
			);

		$database->insert('umeg', $arr);
      	
      	
