<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->user=D('User');
	}
    public function api(){
        $data=i('param.');
        if($data=$this->user->create($data)){
        	$oneUser=$this->user->field('id,username,avatar,level,birthday,phone,pass')->where(array('phone'=>$data['phone']))->find();
	        if($oneUser){
	        	if($oneUser['pass'] == $data['pass']){
	        		unset($oneUser['pass']);
	        		$this->apiReturn(200,'登录成功！',$oneUser);
	        	}else{
	        		$this->apiReturn(403,'密码错误！');
	        	}
	        }else{
	        	$this->apiReturn(402,'未找到该用户！');
	        }
		}else{
			$message=$this->user->getError();
			$this->apiReturn(401,$message);
		}
	}
}