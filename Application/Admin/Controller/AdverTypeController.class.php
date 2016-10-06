<?php
namespace Admin\Controller;
use Think\Controller;

class AdverTypeController extends BaseController{
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
					$this->success('删除成功！',U('AdverType/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}
		}else{
			$advertypelist=$this->advertype->select();
			$this->assign('advertypelist',$advertypelist);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($this->advertype->create($data)){
				if($this->advertype->add()){
					$this->success('添加成功！',U('AdverType/index'),2);
				}else{
					$this->error('添加失败！');
				}
			}else{
				$this->error($this->advertype->getError());
			}
		}else{
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($this->advertype->create($data)){
				if($this->advertype->save()){
					$this->success('修改成功！',U('AdverType/index'),2);
				}else{
					$this->error('修改失败！');
				}
			}else{
				$this->error($this->advertype->getError());
			}
		}else{
			$data['id']=I('param.id');
			$oneAdvertype=$this->advertype->where($data)->find();
			$this->assign('oneAdvertype',$oneAdvertype);
			$this->display();
		}
	}
	public function del(){
		$id=I('get.id');
		if($this->advertype->delete($id)){
			$this->success('删除成功！',U('AdverType/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneAdvertype=$this->advertype->where($data)->find();
		if($oneAdvertype['status'] == 1){
			$data['status'] = 0;
			if($this->advertype->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->advertype->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
	public function getAdverTypeInfo(){
		$data['id']=I('param.id');
		$oneAdvertype=$this->advertype->where($data)->find();
		if($oneAdvertype){
			$response=array('errno'=>0,'width'=>$oneAdvertype['width'],'height'=>$oneAdvertype['height']);
		}else{
			$response=array('errno'=>1,'errmsg'=>'未找到相关广告类型');
		}
		$this->ajaxReturn($response,'JSON');
	}
}