<?php
namespace Admin\Controller;
use Think\Controller;

class ServiceController extends BaseController{
	private $service=null;
	private $cate=null;
	public function __construct(){
		parent::__construct();
		$this->service=D('Service');
		$this->cate=D('Category');
	}
	public function index(){
		$topCates=$this->cate->field('id,name')->where(array('pid'=>0,'groupid'=>3,'type'=>1))->select();
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
				if($this->service->delete($idstr)){
					$this->success('删除成功！',U('Admin/Service/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_service SET sort='$value' WHERE id='$key'";
					$this->service->execute($sql);
				}
				$this->success('排序成功！',U('Admin/Service/index/'.$paramStr),2);
			}elseif($data['action'] == 'check'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_service SET status=1 WHERE id='$id' LIMIT 1";
					$this->service->execute($sql);
				}
				$this->success('批量审核成功！',U('Admin/Service/index/'.$paramStr));
			}elseif($data['action'] == 'rec'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_service SET isrec=1 WHERE id='$id' LIMIT 1";
					$this->service->execute($sql);
				}
				$this->success('批量推荐成功！',U('Admin/Service/index/'.$paramStr),2);
			}elseif($data['action'] == 'search'){
				$map['cateid']=array('in',$ids);
				$map['title']=array('like','%'.$data['q'].'%');
				$total=$this->service->where($map)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$list=$this->service->alias('a')->join('app_category as b ON a.cateid=b.id')->field('a.id,a.title,a.mainpic,a.thumbpic,a.tags,a.price,a.location,a.phone,a.sort,a.status,a.isrec,a.collectnum,a.admirenum,b.name as typename')->where($map)->order('sort')->limit($page->firstRow.','.$page->listRows)->select();
				foreach($list as $value){
					$value['tags']=explode(',',$value['tags']);
					$servicelist[]=$value;
				}
				$this->assign('topCates',$topCates);
				$this->assign('page',$show);
				$this->assign('servicelist',$servicelist);
				$this->assign('cid',$cid);
				$this->display();
			}
		}else{
			$map['cateid']=array('in',$ids);
			$total=$this->service->where($map)->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$list=$this->service->alias('a')->join('app_category as b ON a.cateid=b.id')->field('a.id,a.title,a.mainpic,a.thumbpic,a.tags,a.price,a.location,a.phone,a.sort,a.status,a.isrec,a.collectnum,a.admirenum,b.name as typename')->where($map)->order('sort')->limit($page->firstRow.','.$page->listRows)->select();
			foreach($list as $value){
				$value['tags']=explode(',',$value['tags']);
				$servicelist[]=$value;
			}
			$this->assign('topCates',$topCates);
			$this->assign('page',$show);
			$this->assign('servicelist',$servicelist);
			$this->assign('cid',$cid);
			$this->display();
		}
	}
	public function del(){
		$data=I('param.');
		if($this->service->delete($data['id'])){
			$this->success('删除成功！',U('Admin/Service/index/cid/'.$data['cid']),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneService=$this->service->where($data)->find();
		if($oneService['status'] == 1){
			$data['status'] = 0;
			if($this->service->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->service->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
	public function isRec(){
		$data['id']=I('param.id');
		$oneService=$this->service->field('id,isrec')->where($data)->find();
		if($oneService['isrec'] == 1){
			$data['isrec'] = 0;
			if($this->service->save($data)){
				$response=array('errno'=>0,'isrec'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['isrec'] = 1;
			if($this->service->save($data)){
				$response=array('errno'=>0,'isrec'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
}