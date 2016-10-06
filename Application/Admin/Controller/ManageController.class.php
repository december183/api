<?php
namespace Admin\Controller;
use Think\Controller;

class ManageController extends BaseController{
	public function resetPass(){
		if(IS_POST){
			$data=I('param.');
			$data['id']=session('user.id');
			if($_FILES['pic']['tmp_name']){
				$path=$this->upload();
				$imgArr=getimagesize($path);
				if($imgArr[0] < 600 && $imgArr[1] < 600){
					$path=str_replace('\\', '/',$path);
					$data['head_img']=strstr($path,__ROOT__.'/Uploads/image/');
				}else{
					$data['head_img']=$this->thumb($path);
				}
			}
			if($data=$this->user->create($data)){
				if(isset($data['userpass'])){
					$data['userpass']=password($data['userpass']);
				}
				if($this->user->save($data)){
					$this->success('修改成功！');
				}else{
					$this->error('修改失败！');
				}
			}else{
				$this->error($this->user->getError());
			}
		}else{
			$oneManage=$this->user->field('username,email,phone,head_img,remark')->where(array('id'=>session('user.id')))->find();
			$this->assign('oneManage',$oneManage);
			$this->display('resetpass');
		}
	}
	public function checkPass(){
		$data['userpass']=password(I('param.userpass'));
		$oneUser=$this->user->field('id')->where(array('userpass'=>$data['userpass']))->find();
		if($oneUser){
			$response=array('errno'=>0,'errmsg'=>'原密码输入正确！');
		}else{
			$response=array('errno'=>1,'errmsg'=>'原密码输入错误！');
		}
		$this->ajaxReturn($response,'JSON');
	}
	public function index(){
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->user->delete($ids)){
					$this->success('删除成功！',U('Manage/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}
		}else{
			$total=$this->user->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$managelist=$this->user->alias('a')->join('app_group as b ON a.groupid=b.id')->field('a.id,a.username,a.email,a.phone,a.head_img,a.remark,a.status,a.createtime,b.groupname')->order('createtime DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('managelist',$managelist);
			$this->assign('page',$page);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			$auth1=$this->group->where(array('id'=>$data['groupid']))->find();
			$auth2=$this->group->where(array('id'=>session('user.groupid')))->find();
			if(strrpos($auth2['auth'], $auth1['auth']) == false){
				$this->error('不能添加大于自身权限的用户');
			}
			if($_FILES['pic']['tmp_name']){
				$path=$this->upload();
				$imgArr=getimagesize($path);
				if($imgArr[0] < 600 && $imgArr[1] < 600){
					$path=str_replace('\\', '/',$path);
					$data['head_img']=strstr($path,__ROOT__.'/Uploads/image/');
				}else{
					$data['head_img']=$this->thumb($path);
				}
			}else{
				$data['head_img']=__ROOT__.'/Public/Admin/img/no-thumb.jpg';
			}

			if($this->user->create($data)){
				if($this->user->add()){
					$this->success('新增成功！',U('Manage/index'),2);
				}else{
					$this->error('新增失败！');
				}
			}else{
				$this->error($this->user->getError());
			}
		}else{
			$grouplist=$this->group->where(array('status'=>1))->select();
			$this->assign('grouplist',$grouplist);
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			$auth1=$this->group->where(array('id'=>$data['groupid']))->find();
			$auth2=$this->group->where(array('id'=>session('user.groupid')))->find();
			if(strrpos($auth2['auth'], $auth1['auth']) == false){
				$this->error('不能添加大于自身权限的用户');
			}
			if($_FILES['pic']['tmp_name']){
				$path=$this->upload();
				$imgArr=getimagesize($path);
				if($imgArr[0] < 600 && $imgArr[1] < 600){
					$path=str_replace('\\', '/',$path);
					$data['head_img']=strstr($path,__ROOT__.'/Uploads/image/');
				}else{
					$data['head_img']=$this->thumb($path);
				}
			}
			if($data=$this->user->create($data)){
				if(isset($data['userpass'])){
					$data['userpass']=password($data['userpass']);
				}
				if($this->user->save($data)){
					$this->success('修改成功！',U('Manage/index'),2);
				}else{
					$this->error('修改失败！');
				}
			}else{
				$this->error($this->user->getError());
			}
		}else{
			$data['id']=I('param.id');
			$oneManage=$this->user->where($data)->find();
			$grouplist=$this->group->where(array('status'=>1))->select();
			$this->assign('grouplist',$grouplist);
			$this->assign('oneManage',$oneManage);
			$this->display();
		}
	}
	public function del(){
		$id=I('get.id');
		$oneManage=$this->user->where(array('id'=>$id))->find();
		$auth1=$this->group->where(array('id'=>$oneManage['groupid']))->find();
		$auth2=$this->group->where(array('id'=>session('user.groupid')))->find();
		if(strrpos($auth2['auth'], $auth1['auth']) == false){
			$this->error('不能删除大于自身权限的用户');
		}
		if($this->user->delete($id)){
			$this->success('删除成功！',U('Manage/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneManage=$this->user->where($data)->find();
		if($oneManage['status'] == 1){
			$data['status'] = 0;
			if($this->user->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->user->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
	public function search(){
		$query=I('param.q');
		$condition['username']=array('LIKE','%'.$query.'%');
        $managelist=$this->user->where($condition)->order('createtime ASC')->select();
        $this->assign('managelist',$managelist);
        $this->display('Manage/search');
	}
}