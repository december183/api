<?php
namespace Admin\Controller;
use Think\Controller;

class AuthController extends BaseController{
	public function index(){
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				$ids=$this->auth->getDelIds($data['id']);
				if($this->auth->delete($ids)){
					$this->success('删除成功！',U('Auth/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}
		}else{
			$authlist=$this->auth->getAllResortAuth();
			$this->assign('authlist',$authlist);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($this->auth->create($data)){
				if($insertId=$this->auth->add()){
					$data2['id']=session('user.groupid');
					$userGroupAuth=$this->group->field('auth')->where(array('id'=>session('user.groupid')))->find();
					$data2['auth']=$userGroupAuth['auth'].','.$insertId;
					$this->group->save($data2);
					$this->success('添加菜单成功！',U('Auth/index'),2);

				}else{
					$this->error('添加菜单失败！');
				}
			}else{
				$this->error($this->auth->getError());
			}
		}else{
			$data['id']=I('param.id');
			if($data['id']){
				$oneParentNode=$this->auth->where($data)->find();
				$this->assign('oneParentNode',$oneParentNode);
			}else{
				$authlist=$this->auth->getAllResortAuth();
				$this->assign('authlist',$authlist);
			}
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($this->auth->create($data)){
				if($this->auth->save()){
					$this->success('修改菜单成功！',U('Auth/index'),2);
				}else{
					$this->error('修改菜单失败！');
				}
			}else{
				$this->error($this->auth->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneAuth=$this->auth->where($data)->find();
			$authlist=$this->auth->getAllResortAuth();
			$this->assign('authlist',$authlist);
			$this->assign('oneAuth',$oneAuth);
			$this->display();
		}
	}
	public function del(){
		$data['id']=I('param.id');
		$ids=$this->auth->getDelIds($data['id']);
		if($this->auth->delete($ids)){
			$this->success('删除成功！',U('Auth/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
}