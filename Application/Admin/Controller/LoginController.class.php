<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->user=D('Manage');
	}
    public function index(){
    	if(IS_POST){
    		$data=I('param.');
    		if($data=$this->user->create($data,4)){
    			if($this->user->login($data)){
    				$this->success('登录成功！',U('Index/index'),2);
    			}else{
    				$this->error($this->user->getError());
    			}
    		}else{
    			$this->error($this->user->getError());
    		}
    	}else{
            if(session('user')){
                $this->redirect('Index/index');
            }
    		$this->display();
    	}
    }
}