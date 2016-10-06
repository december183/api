<?php
namespace Admin\Controller;
use Think\Controller;

class ConfigController extends BaseController{
	protected $config=null;
	public function __construct(){
		parent::__construct();
		$this->config=D('Config');
	}
	public function index(){
		if(IS_POST){
			$data=I('param.');
			if($data && is_array($data)){
				foreach($data as $key=>$value){
					$map=array('name'=>$key);
					$this->config->where($map)->setField('value',$value);
				}
				$this->success('保存成功！',U('Config/index'),2);
			}
		}else{
			$configlist=$this->config->where(array('conf_group'=>1))->select();
			$this->assign('configlist',$configlist);
			$this->display();
		}
	}
	public function other(){
		if(IS_POST){
			$data=I('param.');
			if($data && is_array($data)){
				foreach($data as $key=>$value){
					$map=array('name'=>$key);
					$this->config->where($map)->setField('value',$value);
				}
				$this->success('保存成功！',U('Config/other'),2);
			}
		}else{
			$configlist=$this->config->where(array('conf_group'=>2))->select();
			$this->assign('configlist',$configlist);
			$this->display();
		}
	}
}