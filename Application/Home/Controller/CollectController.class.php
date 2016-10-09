<?php
namespace Home\Controller;
use Think\Controller;

class CollectController extends Controller{
	public function __construct(){
		parent::__construct();
		$this->collect=D('Collect');
	}
	public function addApi(){
		$data=I('param.');
		if($this->collect->create($data)){
			$oneCollect=$this->collect->where($data)->find();
			if($oneCollect){
				$this->apiNotice(402,'该商品已收藏');
			}
			if($this->collect->add()){
				$this->apiNotice(200,'收藏成功');
			}else{
				$this->apiNotice(404,'收藏失败');
			}
		}else{
			$this->apiNotice(401,$this->collect->getError());
		}
	}
}