<?php
namespace Admin\Controller;
use Think\Controller;

class GroupController extends BaseController{
	public function index(){
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				if(is_array($data['id'])){
					$map['groupid']=array('IN',$data['id']);
				}else{
					$map['groupid']=array('EQ',$data['id']);
				}
				$userdata=$this->user->field('id')->where($map)->select();
				$groupUser=toOneDimensionalArray($userdata,'id');
				$ids=implode(',',$groupUser);
				$groupids=implode(',',$data['id']);
				$flag1=$this->user->delete($ids);
				$flag2=$this->group->delete($groupids);
				if($flag1 && $flag2){
					$this->success('删除成功！',U('Group/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}
		}else{
			$grouplist=$this->group->select();
			$this->assign('grouplist',$grouplist);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			$data['auth']=implode(',',$data['auth']);
			if($this->group->create($data)){
				if($this->group->add()){
					$this->success('添加用户组成功！',U('Group/index'),2);
				}else{
					$this->error('添加用户组失败！');
				}
			}else{
				$this->error($this->group->getError());
			}
		}else{
			$authlist=$this->auth->getAllResortAuth();
			$this->assign('authlist',$authlist);
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			$data['auth']=implode(',',$data['auth']);
			if($this->group->create($data)){
				if($this->group->save()){
					$this->success('修改用户组成功！',U('Group/index'),2);
				}else{
					$this->error('修改用户组失败！');
				}
			}else{
				$this->error($this->group->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneGroup=$this->group->where($data)->find();
			$authlist=$this->auth->getAllResortAuth();
			$this->assign('authlist',$authlist);
			$this->assign('oneGroup',$oneGroup);
			$this->display();
		}
	}
	public function del(){
		$id=I('param.id');
		$map['groupid']=array('eq',$id);
		$data=$this->user->field('id')->where($map)->select();
		$groupUser=toOneDimensionalArray($data,'id');
		$ids=implode(',',$groupUser);
		$flag1=$this->user->delete($ids);
		$flag2=$this->group->delete($id);
		if($flag1 && $flag2){
			$this->success('删除成功！',U('Group/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneGroup=$this->group->where($data)->find();
		if($oneGroup['status'] == 1){
			$data['status'] = 0;
			if($this->group->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->group->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
}