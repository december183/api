<?php
namespace Home\Controller;
use Think\Controller;

class FeedbackController extends Controller{
	private $feedback=null;
	public function __construct(){
		parent::__construct();
		$this->feedback=D('Feedback');
	}
	public function indexApi(){
		$data=I('param.');
		if($this->feedback->create($data)){
			if($this->feedback->add()){
				$this->apiReturn(200,'意见提交成功');
			}else{
				$this->apiReturn(404,'意见提交失败');
			}
		}else{
			$this->apiReturn(401,$this->feedback->getError());
		}
	}
}