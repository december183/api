<?php
	$dsn='mysql:host=localhost;dbname=86club_www;charset=UTF8';
	$user='86club_www';
	$pass='fmWHqwTmW3njvahj';
	try{
		$db=new PDO($dsn,$user,$pass);
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e){
		echo 'Connection failed:'.$e->getMessage();
	}
	$time=time();
	$sql="SELECT id,status,date FROM app_order WHERE status=4";
	$stmt=$db->query($sql);
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		if($row['date'] < ($time-24*3600)){
			$sql="UPDATE app_order SET status=0 WHERE id='{$row['id']}'";
			$db->exec($sql);
		}
	}