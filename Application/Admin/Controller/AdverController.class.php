<?php
namespace Admin\Controller;
use Think\Controller;

class AdverController extends BaseController{
	private $advertype=null;
	public function __construct(){
		parent::__construct();
		$this->advertype=D('AdverType');
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
				$ids=implode(',',$data['id']);
				if($this->adver->delete($ids)){
					$this->success('删除成功！',U('Adver/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_adver SET sort='$value' WHERE id='$key'";
					$this->adver->execute($sql);
				}
				$this->success('排序成功！',U('Admin/Adver/index/'.$paramStr),2);
			}elseif($data['action']=='search'){
				if($data['q']){
					$condition['a.title']=array('LIKE','%'.$data['q'].'%');
				}
		        $adverlist=$this->adver->alias('a')->join('app_adver_type as b ON a.typeid=b.id')->field('a.id,a.title,a.thumb,a.type,a.url,a.agerange,a.sort,a.status,a.date,b.name as typename')->where($condition)->order('sort ASC')->select();
		        $this->assign('adverlist',$adverlist);
		        $this->display();
			}
		}else{
			$total=$this->adver->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$adverlist=$this->adver->alias('a')->join('app_adver_type as b ON a.typeid=b.id')->field('a.id,a.title,a.thumb,a.type,a.url,a.agerange,a.sort,a.status,a.date,b.name as typename')->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('adverlist',$adverlist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($_FILES['pic']['tmp_name']){
				$path=$this->thumb($this->upload(),$data['width'],$data['height']);
				$path=str_replace('\\', '/',$path);
				$data['thumb']=strstr($path,__ROOT__.'/Uploads/image/');
			}else{
				$data['thumb']=__ROOT__.'/Public/Admin/img/no-thumb.jpg';
			}
			if($data=$this->adver->create($data)){
				if($this->adver->add($data)){
					$this->success('新增广告成功！',U('Adver/index'),2);
				}else{
					$this->error('新增广告失败！');
				}
			}else{
				$this->error($this->adver->getError());
			}
		}else{
			$typelist=$this->advertype->where(array('status'=>1))->select();
			$this->assign('typelist',$typelist);
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($_FILES['pic']['tmp_name']){
				$path=$this->thumb($this->upload(),$data['width'],$data['height']);
				$path=str_replace('\\', '/',$path);
				$data['thumb']=strstr($path,__ROOT__.'/Uploads/image/');
			}
			if($this->adver->create($data)){
				if($this->adver->save()){
					$this->success('修改广告成功！',U('Adver/index'),2);
				}else{
					$this->error('修改广告失败！');
				}
			}else{
				$this->error($this->adver->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneAdver=$this->adver->where($data)->find();
			$typelist=$this->advertype->where(array('status'=>1))->select();
			$this->assign('typelist',$typelist);
			$this->assign('oneAdver',$oneAdver);
			$this->display();
		}
	}
	public function del(){
		$id=I('get.id');
		if($this->adver->delete($id)){
			$this->success('删除成功！',U('Adver/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function isRec(){
		$data['id']=I('param.id');
		$oneAdver=$this->adver->where($data)->find();
		if($oneAdver['status'] == 1){
			$data['status'] = 0;
			if($this->adver->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->adver->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
}
