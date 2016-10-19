<?php
namespace Home\Controller;
use Think\Controller;

class DiscountController extends Controller{
	private $event=null;
	private $seller=null;
	public function __construct(){
		parent::__construct();
		$this->event=D('Event');
		$this->seller=D('User');
	}
	public function indexApi(){
		$eventlist=$this->event->field('id,title,thumbpic,endtime')->where(array('status'=>1,'isrec'=>1))->order('sort ASC')->limit(4)->select();
		$map=array('isrec'=>1,'level'=>3,'status'=>1);
		$total=$this->seller->where($map)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$sellerlist=$this->seller->field('id,shopbanner')->where($map)->order('date ASC')->limit($page->firstRow.','.$page->listRows)->select();
		if($sellerlist && $eventlist){
			$data=array('event'=>$eventlist,'seller'=>$sellerlist);
			$this->apiReturn(200,'返回优惠专区数据成功',$data);
		}else{
			$this->apiReturn(404,'返回优惠专区数据失败');
		}
	}
}