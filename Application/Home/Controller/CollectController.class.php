<?php
namespace Home\Controller;
use Think\Controller;

class CollectController extends Controller{
	private $collect=null;
	private $service=null;
	private $event=null;
	public function __construct(){
		parent::__construct();
		$this->collect=D('Collect');
		$this->service=D('Service');
		$this->event=D('Event');
	}
	public function addApi(){
		$data=I('param.');
		$oneCollect=$this->collect->where($data)->find();
		if($oneCollect){
			if($this->collect->delete($oneCollect['id'])){
				if(isset($data['serviceid'])){
					$this->service->where(array('id'=>$data['serviceid']))->setDec('collectnum');
				}elseif(isset($data['eventid'])){
					$this->event->where(array('id'=>$data['eventid']))->setDec('collectnum');
				}
				$this->apiReturn(200,'取消收藏成功');
			}else{
				$this->apiReturn(404,'取消收藏失败');
			}
		}else{
			if($this->collect->create($data)){
				if($this->collect->add()){
					if(isset($data['serviceid'])){
						$this->service->where(array('id'=>$data['serviceid']))->setInc('collectnum');
					}elseif(isset($data['eventid'])){
						$this->event->where(array('id'=>$data['eventid']))->setInc('collectnum');
					}
					$this->apiReturn(200,'收藏成功');
				}else{
					$this->apiReturn(404,'收藏失败');
				}
			}else{
				$this->apiReturn(401,$this->collect->getError());
			}
		}
	}
	public function deleteApi(){
		$data=I('param.');
		if($this->collect->create($data)){
			$oneCollect=$this->collect->where()->find();

		}
	}
	public function indexApi(){
		$data=I('param.');
		$map['uid']=$data['uid'];
		if(isset($data['eventid'])){
			$map['eventid']=0;
			$list=$this->collect->field('serviceid')->where($map)->select();
			foreach($list as $value){
				$serviceids.=$value['serviceid'].',';
			}
			$serviceids=substr($serviceids,0,-1);
			$condition['id']=array('in',$serviceids);
			$total=$this->service->where($condition)->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$servicelist=$this->service->field('title,thumbpic')->where($condition)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			if($servicelist){
				$this->apiReturn(200,'返回会员收藏列表成功',$servicelist);
			}else{
				$this->apiReturn(404,'暂无会员商品收藏数据');
			}
		}elseif(isset($data['serviceid'])){
			$map['serviceid']=0;
			$list=$this->collect->field('eventid')->where($map)->select();
			foreach($list as $value){
				$eventids.=$value['eventid'].',';
			}
			$eventids=substr($eventids,0,-1);
			$condition['id']=array('in',$eventids);
			$total=$this->event->where($condition)->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$eventlist=$this->event->field('title,thumbpic')->where($condition)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			if($eventlist){
				$this->apiReturn(200,'返回会员收藏列表成功',$eventlist);
			}else{
				$this->apiReturn(404,'暂无会员活动收藏数据');
			}
		}else{
			$this->apiReturn(400,'参数错误');
		}
	}
}