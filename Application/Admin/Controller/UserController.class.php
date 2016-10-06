<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends BaseController{
	private $customer=null;
	public function __construct(){
		parent::__construct();
		$this->customer=D('User');
	}
	public function index(){
		$map['level']=array('eq',1);
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->customer->delete($ids)){
					$this->success('删除成功！',U('User/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action'] == 'check'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_user SET status=1 WHERE id='$id' LIMIT 1";
					$this->customer->execute($sql);
				}
				$this->success('批量审核成功！',U('User/index'),2);
			}elseif($data['action'] == 'search'){
				$map['username']=array('like','%'.$data['q'].'%');
				$total=$this->customer->where($map)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$userlist=$this->customer->where($map)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
				$this->assign('userlist',$userlist);
				$this->assign('page',$show);
				$this->display();
			}
		}else{
			$total=$this->customer->where($map)->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$userlist=$this->customer->where($map)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('userlist',$userlist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneUser=$this->customer->where($data)->find();
		if($oneUser['status'] == 1){
			$data['status'] = 0;
			if($this->customer->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->customer->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
	public function del(){
		$id=I('get.id');
		if($this->customer->delete($id)){
			$this->success('删除成功！',U('User/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
}