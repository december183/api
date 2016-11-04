<?php
namespace Home\Controller;
use Think\Controller;

class ApplyController extends Controller{
	private $apply=null;
	private $event=null;
	public function __construct(){
		parent::__construct();
		$this->apply=D('Apply');
		$this->event=D('Event');
	}
	public function indexApi(){
		if(IS_POST){
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
		}else{
			$data['a.id']=I('param.id');
			$oneEvent=$this->event->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.title,a.content,a.starttime,a.endtime,a.place,a.price,b.username')->where($data)->find();
			if($oneEvent){
				$this->apiReturn(200,'返回活动信息成功',$oneEvent);
			}else{
				$this->apiReturn(404,'暂无该活动信息');
			}
		}
	}
}