<?php
	$array=array(12,5,26,7,17,9);
	$len=count($array);
	for($i=1;$i<$len;$i++){
		$key=$array[$i];
		$j=$i-1;
		while($j >= 0 && $key < $array[$j]){
			$array[$j + 1]=$array[$j];
			$j-=1;
		}
		$array[$j + 1]=$key;
	}
	print_r($array);