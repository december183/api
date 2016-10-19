<?php
namespace Home\Controller;
use Think\Controller;

class OrderController extends Controller{
	private $order=null;
	private $goods=null;
	private $service=null;
	public function __construct(){
		parent::__construct();
		$this->order=D('Order');
		$this->goods=D('Goods');
		$this->service=D('Service');
	}
	public function addApi(){
		$data=I('param.');
		$data['order_no']=getTradeNo($data['type']);
		$data['info']=toOneDimensionalArray($data['info'],'id');
		foreach($data['info'] as $key=>$value){
			if($data['type'] == 1){
				$oneGoods=$this->goods->field('price')->where(array('id'=>$key))->find();
			}else{
				$oneGoods=$this->service->field('price')->where(array('id'=>$key))->find();
			}
			$data['totalfee']+=$oneGoods['price']*$value;
		}
		$data['info']=json_encode($data['info']);
		if($data=$this->order->create($data)){
			if($this->order->add()){
				$this->apiReturn(200,'生成订单成功');
			}else{
				$this->apiReturn(404,'生成订单失败');
			}
		}else{
			$this->apiReturn($this->order->getError());
		}
	}
	public function userIndexApi(){
		$data=I('param.');
		if(isset($data['uid'])){
			$total=$this->order->where($data)->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$list=$this->order->field('id,type,info,totalfee,status')->where($data)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			if($list){
				$orderlist=array();
				foreach($list as $value){
					$value['info']=json_decode($value['info'],true);
					if($value['type'] == 1){
						foreach($value['info'] as $key=>$val){
							$oneService=$this->service->field('id,price,discountprice,title,thumbnail,location')->where(array('id'=>$key))->find();
							$oneService['num']=$val;
							$value['detail'][]=$oneService;
						}
					}else{
						foreach($value['info'] as $key=>$val){
							$oneGoods=$this->goods->field('id,price,discountprice,title,thumbnail,sn')->where(array('id'=>$key))->find();
							$oneGoods['num']=$val;
							$value['detail'][]=$oneGoods;
						}
					}
					$orderlist[]=$value;
				}
				$this->apiReturn(200,'返回用户订单列表成功',$orderlist);
			}else{
				$this->apiReturn(404,'暂无用户订单信息');
			}
		}else{
			$this->apiReturn(401,'参数错误,需传入当前登陆用户ID');
		}
	}
	
}