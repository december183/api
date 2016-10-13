<?php
namespace Home\Controller;
use Think\Controller;

class ApplyController extends Controller{
	private $apply=null;
	public function __construct(){
		parent::__construct();
		$this->apply=D('Apply');
	}
	public function indexApi(){
		$data=I('param.');
		if($this->apply->create($data)){
			if($this->apply->add()){
				$this->apiReturn(200,'活动报名成功');
			}else{
				$this->apiReturn(404,'报名失败');
			}
		}else{
			$this->apiReturn(401,$this->apply->getError());
		}
	}
}