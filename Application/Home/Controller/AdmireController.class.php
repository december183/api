<?php
namespace Home\Controller;
use Think\Controller;

class AdmireController extends Controller{
	private $admire=null;
	private $service=null;
	private $comment=null;
	public function __construct(){
		parent::__construct();
		$this->admire=D('Admire');
		$this->service=D('Service');
		$this->comment=D('Comment');
	}
	public function addApi(){
		$data=I('param.');
		if($this->admire->create($data)){
			$oneAdmire=$this->admire->where($data)->find();
			if($oneAdmire){
				$this->apiReturn(404,'已点赞');
			}
			if($this->admire->add()){
				if(isset($data['serviceid'])){
					$this->service->where(array('id'=>$data['serviceid']))->setInc('admirenum');
				}elseif(isset($data['commentid'])){
					$this->comment->where(array('id'=>$data['commentid']))->setInc('agreenum');
				}
				$this->apiReturn(200,'点赞成功');
			}else{
				$this->apiReturn(404,'点赞失败');
			}
		}else{
			$this->apiReturn(401,$this->admire->getError());
		}
	}
}