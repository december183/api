<?php
namespace Home\Controller;
use Think\Controller;
class RegController extends Controller {
	private $user=D('User');
	public function __construct(){
		parent::__construct();
		$this->user=D('User');
	}
    public function api(){
        $data=I('param.');
        if($data=$this->user->create($data)){
        	if($data['mscode'] == $_SESSION['mscode']){
                $result=$this->user->add($data);
        		if($result){
	        		$this->apiReturn(200,'注册成功！',array('id'=>$result));
	        	}else{
	        		$this->apiNotice(403,'注册失败！');
	        	}
        	}else{
        		$this->apiNotice(402,'短信验证码失败！');
        	}
        }else{
        	$message=$this->user->getError();
        	$this->apiNotice(401,$message);
        }
    }
}