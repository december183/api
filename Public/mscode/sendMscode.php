<?php
	session_start();
	require_once('lib/nusoap-for-php5.3.php');
	$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);
	$client->soap_defencoding = 'utf-8';
	$client->decode_utf8      = false;
	$client->xml_encoding     = 'utf-8';
	$err = $client->getError();
	if ($err) {
	    echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	}
	$code=$msgText='';
	for($i=0;$i<6;$i++){
		$code.=mt_rand(0,9);
	}
	$data=$_GET;
	if(isset($data['phone']) && isset($data['type'])){
		$_SESSION[$data['phone'].$data['type']]=$code;
		$sessionId=session_id();
		if($data['type'] == 1){
			$msgText='欢迎注册妈妈应用,您的短信验证码为'.$code.',10分钟内有效【妈妈应用】';
		}elseif($data['type'] == 2){
			$msgText='您正在修改妈妈应用密码,确认是本人操作，您的短信验证码为'.$code.',10分钟内有效【妈妈应用】';
		}elseif($data['type'] == 3){
			$msgText='您正在修改妈妈应用绑定手机号,确认是本人操作，您的短信验证码为'.$code.',10分钟内有效【妈妈应用】';
		}
		$params = array(
		    'account' => 'sdk_mmsq',
		    'password' => 'jvwo9q',
		    'destmobile' => $data['phone'],
		    'msgText' => $msgText,
		);
		$result = $client->call('sendBatchMessage', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');
		if ($client->fault) {
		    echo '<h2>Fault (This is expected)</h2><pre>'; print_r($result); echo '</pre>';
		} else {
		    $err = $client->getError();
		    if ($err) {
		        echo '<h2>Error</h2><pre>' . $err . '</pre>';
		    } else {
		        //echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
		        if($result['sendBatchMessageReturn'] > 0){
		        	$data=array('session_id'=>$sessionId);
		        	$arr=array(
		        		'status'=>200,
		        		'message'=>'短信发送成功',
		        		'data'=>$data
		        	);
		        	echo json_encode($arr);
		        }
		    }
		}
	}
	