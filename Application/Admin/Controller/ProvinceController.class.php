<?php
namespace Admin\Controller;
use Think\Controller;

class ProvinceController extends BaseController{
	private $province=null;
	public function __construct(){
		parent::__construct();
		$this->province=D('Province');
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
				if($this->province->delete($idstr)){
					$this->success('删除成功！',U('Admin/Province/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_province SET sort='$value' WHERE id='$key'";
					$this->province->execute($sql);
				}
				$this->success('排序成功！',U('Admin/Province/index/'.$paramStr),2);
			}
		}else{
			$total=$this->province->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$provincelist=$this->province->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('provincelist',$provincelist);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($data=$this->province->create($data)){
				if($this->province->add($data)){
					$this->success('新增省分成功！',U('Province/index'),2);
				}else{
					$this->error('新增省分失败！');
				}
			}else{
				$this->error($this->province->getError());
			}
		}else{
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($this->province->create($data)){
				if($this->province->save()){
					$this->success('修改省分成功！',U('Province/index'),2);
				}else{
					$this->error('修改省分失败！');
				}
			}else{
				$this->error($this->province->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneProvince=$this->province->where($data)->find();
			$this->assign('oneProvince',$oneProvince);
			$this->display();
		}
	}
	public function del(){
		$data['id']=I('param.id');
		if($this->province->delete($data['id'])){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
}