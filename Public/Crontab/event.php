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
	$sql="SELECT id,starttime,endtime FROM app_event WHERE status<>5";
	$stmt=$db->query($sql);
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		if($time > strtotime($row['starttime']) && $time < strtotime($row['endtime'])){
			$sql="UPDATE app_event SET status=4 WHERE id='{$row['id']}'";
			$db->exec($sql);
		}elseif($time > strtotime($row['endtime'])){
			$sql="UPDATE app_event SET status=5 WHERE id='{$row['id']}'";
			$db->exec($sql);
		}
	}
