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
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->adver->delete($ids)){
					$this->success('删除成功！',U('Adver/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}
		}else{
			$total=$this->adver->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$adverlist=$this->adver->alias('a')->join('app_adver_type as b ON a.typeid=b.id')->field('a.id,a.title,a.thumb,a.type,a.url,a.agerange,a.status,a.date,b.name as typename')->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
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
			if($this->adver->create($data)){
				if($this->adver->add()){
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
	public function search(){
		$query=I('param.q');
		$condition['title']=array('LIKE','%'.$query.'%');
        $adverlist=$this->adver->where($condition)->order('date DESC')->select();
        $this->assign('adverlist',$adverlist);
        $this->display('Adver/search');
	}
}
