<?php
namespace Home\Controller;
use Think\Controller;
class RegController extends Controller {
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->user=D('User');
	}
    public function api(){
        $data=I('param.');
        $data['type']=1;
        if($data['mscode'] == $_SESSION[$data['phone'].$data['type']]){
            unset($_SESSION[$data['phone'].$data['type']]);
            if($data=$this->user->create($data,4)){
                $result=$this->user->add($data);
        		if($result){
	        		$this->apiReturn(200,'注册成功！',array('id'=>$result));
	        	}else{
	        		$this->apiReturn(403,'注册失败！');
	        	}
        	}else{
                $this->apiReturn(401,$this->user->getError());
        	}
        }else{
            $this->apiReturn(402,'短信验证码失败！');
        }
    }
}