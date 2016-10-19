<?php
namespace Home\Controller;
use Think\Controller;

class OrderController extends Controller{
	private $order=null;
	private $service=null;
	private $goods=null;
	public function __construct(){
		parent::__construct();
		$this->order=D('Order');
		$this->service=D('Service');
		$this->goods=D('Goods');
	}
	public function index(){
		$param=I('get.');
		if($param){
			foreach($param as $key=>$value){
				$paramStr.=$key.'/'.$value;
			}
			$map=$param;
		}else{
			$paramStr='type/1';
			$map=array('type'=>1);
		}
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->order->delete($ids)){
					$this->success('删除成功！',U('Admin/Order/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action'] == 'search'){
				$condition['title']=array('like','%'.$data['keywords'].'%');
				if($map['type'] == 1){
					$list=$this->service->field('id')->where($condition)->select();
				}else{
					$list=$this->goods->field('id')->where($condition)->select();
				}
				$allorderlist=$this->order->field('id,info')->where(array('type'=>$map['type']))->select();
				foreach($list as $value){
					$value='"'.$value.'"';
					foreach($allorderlist as $val){
						if(strpos($val['info'],$value) !== false){
							$orderids.=$val['id'].',';
						}
					}
				}
				$map['id']=array('in',$orderids);
				$total=$this->order->where($map)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$list=$this->order->where($map)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
				$orderlist=$this->getOrderInfo($list);
				$this->assign('orderlist',$orderlist);
				$this->assign('page',$show);
				$this->display();
			}
		}else{
			$total=$this->order->where($map)->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$list=$this->order->where($map)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$orderlist=$this->getOrderInfo($list);
			$this->assign('orderlist',$orderlist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	protected function getOrderInfo($list){
		$orderlist=array();
		if($map['type'] == 1){
			foreach($list as $value){
				$info=json_decode($value['info'],true);
				$goodslist=array();
				foreach($info as $key=>$val){
					$oneGoods=$this->service->field('id,title,price')->where(array('id'=>$key))->find();
					$oneGoods['num']=$val;
					$goodslist[]=$oneGoods;
				}
				$value['info']=$goodslist;
				$orderlist[]=$value;
			}
		}else{
			foreach($list as $value){
				$info=json_decode($value['info'],true);
				$goodslist=array();
				foreach($info as $key=>$val){
					$oneGoods=$this->goods->field('id,title,price')->where(array('id'=>$key))->find();
					$oneGoods['num']=$val;
					$goodslist[]=$oneGoods;
				}
				$value['info']=$goodslist;
				$orderlist[]=$value;
			}
		}
		return $orderlist;
	}
}