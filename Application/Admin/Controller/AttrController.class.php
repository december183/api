<?php
namespace Admin\Controller;
use Think\Controller;

class AttrController extends BaseController{
	private $attr=null;
	public function __construct(){
		parent::__construct();
		$this->attr=D('Attr');
	}
	public function index(){
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->attr->delete($ids)){
					$this->success('删除成功！',U('Attr/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}
		}else{
			$total=$this->attr->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$attrlist=$this->attr->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('attrlist',$attrlist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if(!isset($data['value'])){
				$data['value']='';
			}
			if($this->attr->create($data)){
				if($this->attr->add()){
					$this->success('新增属性成功！',U('Attr/index'),2);
				}else{
					$this->error('新增属性失败！');
				}
			}else{
				$this->error($this->attr->getError());
			}
		}else{
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if(!isset($data['value'])){
				$data['value']='';
			}
			if($this->attr->create($data)){
				if($this->attr->save()){
					$this->success('修改属性成功！',U('Attr/index'),2);
				}else{
					$this->error('修改属性失败！');
				}
			}else{
				$this->error($this->attr->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneAttr=$this->attr->where($data)->find();
			$this->assign('oneAttr',$oneAttr);
			$this->display();
		}
	}
	public function del(){
		$id=I('get.id');
		if($this->attr->delete($id)){
			$this->success('删除成功！',U('Attr/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
}
