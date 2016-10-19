<?php
namespace Admin\Controller;
use Think\Controller;

class CreditGoodsController extends BaseController{
	private $creditgoods=null;
	public function __construct(){
		parent::__construct();
		$this->creditgoods=D('CreditGoods');
	}
	public function index(){
		if(IS_POST){
			$data=I('param.');
			$param=I('get.');
			if($param){
				foreach($param as $key=>$value){
					$paramStr.=$key.'/'.$value;
				}
			}
			if($data['action']=='delete'){
				$idstr=implode(',',$data['id']);
				if($this->creditgoods->delete($idstr)){
					$this->success('删除成功！',U('Admin/CreditGoods/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_credit_goods SET sort='$value' WHERE id='$key'";
					$this->creditgoods->execute($sql);
				}
				$this->success('排序成功！',U('Admin/CreditGoods/index/'.$paramStr),2);
			}elseif($data['action'] == 'rec'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_credit_goods SET isrec=1 WHERE id='$id' LIMIT 1";
					$this->creditgoods->execute($sql);
				}
				$this->success('批量推荐成功！',U('Admin/CreditGoods/index/'.$paramStr),2);
			}elseif($data['action'] == 'search'){
				$map['title']=array('like','%'.$data['q'].'%');
				$total=$this->creditgoods->where($map)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$goodslist=$this->creditgoods->where($map)->order('sort')->limit($page->firstRow.','.$page->listRows)->select();
				$this->assign('page',$show);
				$this->assign('goodslist',$goodslist);
				$this->display();
			}
		}else{
			$total=$this->creditgoods->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$goodslist=$this->creditgoods->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$show);
			$this->assign('goodslist',$goodslist);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($this->creditgoods->create($data)){
				if($this->creditgoods->add()){
					$this->success('发布成功',U('CreditGoods/index'),2);
				}else{
					$this->error('发布失败');
				}
			}else{
				$this->error($this->creditgoods->getError());
			}
		}else{
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($this->creditgoods->create($data)){
				if($this->creditgoods->save()){
					$this->success('修改成功',U('CreditGoods/index'),2);
				}else{
					$this->error('修改失败');
				}
			}else{
				$this->error($this->creditgoods->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneGoods=$this->creditgoods->where($data)->find();
			$this->assign('oneGoods',$oneGoods);
			$this->display();
		}
	}
	public function del(){
		$data=I('get.');
		if($this->creditgoods->delete($data['id'])){
			$this->success('删除成功！',U('CreditGoods/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function isRec(){
		$data['id']=I('param.id');
		$oneGoods=$this->creditgoods->field('id,isrec')->where($data)->find();
		if($oneGoods['isrec'] == 1){
			$data['isrec'] = 0;
			if($this->creditgoods->save($data)){
				$response=array('errno'=>0,'isrec'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['isrec'] = 1;
			if($this->creditgoods->save($data)){
				$response=array('errno'=>0,'isrec'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
}