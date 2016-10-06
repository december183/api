<?php
namespace Admin\Controller;
use Think\Controller;

class SellerController extends BaseController{
	private $seller=null;
	public function __construct(){
		parent::__construct();
		$this->seller=D('User');
	}
	public function index(){
		$map['level']=array('neq',1);
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->seller->delete($ids)){
					$this->success('删除成功！',U('Seller/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action'] == 'check'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_user SET level=3 WHERE id='$id' LIMIT 1";
					$this->seller->execute($sql);
				}
				$this->success('批量审核成功！',U('Seller/index'),2);
			}elseif($data['action'] == 'search'){
				$map['shopname']=array('like','%'.$data['q'].'%');
				$sellerlist=$this->seller->where($map)->order('date DESC')->select();
				$this->assign('sellerlist',$sellerlist);
				$this->display();
			}
		}else{
			$total=$this->seller->where($map)->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$sellerlist=$this->seller->where($map)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('sellerlist',$sellerlist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneSeller=$this->seller->where($data)->find();
		if($oneSeller['status'] == 1){
			$data['status'] = 0;
			if($this->seller->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->seller->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
	public function checkSeller(){
		$data['id']=I('param.id');
		$oneSeller=$this->seller->where($data)->find();
		if($oneSeller['level'] == 2){
			$data['level'] = 3;
			if($this->seller->save($data)){
				$response=array('errno'=>0,'level'=>3);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['level'] = 2;
			if($this->seller->save($data)){
				$response=array('errno'=>0,'level'=>2);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
	public function del(){
		$id=I('get.id');
		if($this->seller->delete($id)){
			$this->success('删除成功！',U('Seller/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
}