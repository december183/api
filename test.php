<?php
    // echo phpinfo();
 // 	$memcache = new Memcache;             //创建一个memcache对象
	// $memcache->connect('localhost', 11211) or die ("Could not connect"); //连接Memcached服务器
	// $memcache->set('key', 'test');        //设置一个变量到内存中，名称是key 值是test
	// $get_value = $memcache->get('key');   //从内存中取出key的值
	// echo $get_value;
	// 	
	echo time().'<br/>';
	function password($password){
        for ($i=0; $i < 3; $i++) { 
            $password = sha1(strrev($password));
        }
        return $password;
    }
    echo password('123456');