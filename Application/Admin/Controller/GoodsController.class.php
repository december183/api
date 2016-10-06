<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends BaseController{
	private $goods=null;
	private $cate=null;
	public function __construct(){
		parent::__construct();
		$this->goods=D('Goods');
		$this->cate=D('Category');
	}
	public function index(){
		$topCates=$this->cate->field('id,name')->where(array('pid'=>0,'groupid'=>2,'type'=>1))->select();
		$cid=I('get.cid') ? I('get.cid') : $topCates[0]['id'];
		$ids=$this->cate->getDelIds($cid);
		if(IS_POST){
			$data=I('param.');
			$param=I('get.');
			if($param){
				foreach($param as $key=>$value){
					$paramStr.=$key.'/'.$value;
				}
			}else{
				$paramStr='cid/'.$cid;
			}
			if($data['action']=='delete'){
				$idstr=implode(',',$data['id']);
				if($this->goods->delete($idstr)){
					$this->success('删除成功！',U('Admin/Goods/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_goods SET sort='$value' WHERE id='$key'";
					$this->goods->execute($sql);
				}
				$this->success('排序成功！',U('Admin/Goods/index/'.$paramStr),2);
			}elseif($data['action'] == 'check'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_goods SET status=1 WHERE id='$id' LIMIT 1";
					$this->goods->execute($sql);
				}
				$this->success('批量审核成功！',U('Admin/Goods/index/'.$paramStr));
			}elseif($data['action'] == 'rec'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_goods SET isrec=1 WHERE id='$id' LIMIT 1";
					$this->goods->execute($sql);
				}
				$this->success('批量推荐成功！',U('Admin/Goods/index/'.$paramStr),2);
			}elseif($data['action'] == 'search'){
				$map['cateid']=array('in',$ids);
				$map['title']=array('like','%'.$data['q'].'%');
				$total=$this->goods->where($map)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$list=$this->goods->alias('a')->join('app_category as b ON a.cateid=b.id')->field('a.id,a.title,a.mainpic,a.thumbpic,a.tags,a.brandid,a.sn,a.saleprice,a.inventory,a.sort,a.status,a.isrec,a.collectnum,a.admirenum,b.name as typename')->where($map)->order('a.sort')->limit($page->firstRow.','.$page->listRows)->select();
				foreach($list as $value){
					$value['tags']=explode(',',$value['tags']);
					$goodslist[]=$value;
				}
				$this->assign('topCates',$topCates);
				$this->assign('page',$show);
				$this->assign('goodslist',$goodslist);
				$this->assign('cid',$cid);
				$this->display();
			}
		}else{
			$map['cateid']=array('in',$ids);
			$total=$this->goods->where($map)->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$list=$this->goods->alias('a')->join('app_category as b ON a.cateid=b.id')->field('a.id,a.title,a.mainpic,a.thumbpic,a.tags,a.brandid,a.sn,a.saleprice,a.inventory,a.sort,a.status,a.isrec,a.collectnum,a.admirenum,b.name as typename')->where($map)->order('a.sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			foreach($list as $value){
				$value['tags']=explode(',',$value['tags']);
				$goodslist[]=$value;
			}
			$this->assign('topCates',$topCates);
			$this->assign('page',$show);
			$this->assign('goodslist',$goodslist);
			$this->assign('cid',$cid);
			$this->display();
		}
	}
	public function del(){
		$data=I('get.');
		if($this->goods->delete($data['id'])){
			$this->success('删除成功！',U('Admin/Goods/index/cid/'.$data['cid']),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneGoods=$this->goods->field('id,status')->where($data)->find();
		if($oneGoods['status'] == 1){
			$data['status'] = 0;
			if($this->goods->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->goods->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
	public function isRec(){
		$data['id']=I('param.id');
		$oneGoods=$this->goods->field('id,isrec')->where($data)->find();
		if($oneGoods['isrec'] == 1){
			$data['isrec'] = 0;
			if($this->goods->save($data)){
				$response=array('errno'=>0,'isrec'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['isrec'] = 1;
			if($this->goods->save($data)){
				$response=array('errno'=>0,'isrec'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
}