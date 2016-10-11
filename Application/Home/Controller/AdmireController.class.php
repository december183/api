<?php
namespace Home\Controller;
use Think\Controller;

class AdmireController extends Controller{
	private $admire=null;
	public function __construct(){
		parent::__construct();
		$this->admire=D('Admire');
	}
	public function addApi(){
		$data=I('param.');
		if($this->admire->create($data)){
			$oneAdmire=$this->admire->where($data)->find();
			if($oneAdmire){
				$this->apiReturn(402,'该商品已点赞');
			}
			if($this->admire->add()){
				$this->apiReturn(200,'点赞成功');
			}else{
				$this->apiReturn(402,'点赞失败');
			}
		}else{
			$this->apiReturn(401,$this->admire->getError());
		}
	}
}