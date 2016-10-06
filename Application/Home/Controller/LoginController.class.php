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
        	$oneUser=$this->user->where(array('phone'=>$data['phone']))->find();
	        if($oneUser){
	        	if($oneUser['pass'] == password($data['pass'])){
	        		$this->apiReturn(200,'登录成功！',$oneUser);
	        	}else{
	        		$this->apiNotice(402,'密码错误！');
	        	}
	        }else{
	        	$this->apiNotice(400,'未找到该用户！');
	        }
		}else{
			$message=$this->user->getError();
			$this->apiNotice(401,$message);
		}
	}
}