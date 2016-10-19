<?php
namespace Home\Controller;
use Think\Controller;

class CreditOrderController extends Controller{
	private $creditorder=null;
	private $creditgoods=null;
	public function __construct(){
		parent::__construct();
		$this->creditorder=D('CreditOrder');
		$this->creditgoods=D('CreditGoods');
	}
	public function indexApi(){
		$data=I('param.');
		if(isset($data['uid'])){
			$total=$this->creditorder->where($data)->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$orderlist=$this->creditorder->alias('a')->join('app_credit_order as b ON a.gid=b.id')->field('a.num,a.totalcredit,a.date,b.thumbpic,b.title')->where($data)->order('a.date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			if($orderlist){
				$this->apiReturn(200,'返回已兑商品列表成功',$orderlist);
			}else{
				$this->apiReturn(404,'暂无已兑商品信息');
			}
		}else{
			$this->apiReturn(401,'必须传入当前登录用户ID');
		}
		
	}
	public function addApi(){
		$data=I('param.');
		$data['totalcredit']=$data['num']*$data['credit'];
		$oneGoods=$this->creditgoods->field('inventory')->where(array('id'=>$data['gid']))->find();
		if($oneGoods['inventory']-$data['num'] < 0){
			$this->apiReturn(402,'商品库存不足');
		}else{
			if($this->creditorder->create($data)){
				if($this->creditorder->add()){
					$this->creditgoods->where(array('id'=>$data['gid']))->setDec('inventory',$data['num']);
					$this->apiReturn(200,'兑换商品成功');
				}else{
					$this->apiReturn(404,'兑换商品失败');
				}
			}else{
				$this->apiReturn(401,$this->creditorder->getError());
			}
		}
	}
}