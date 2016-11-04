<?php
namespace Home\Controller;
use Think\Controller;

class CreditOrderController extends Controller{
	private $creditorder=null;
	private $creditgoods=null;
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->creditorder=D('CreditOrder');
		$this->creditgoods=D('CreditGoods');
		$this->user=D('User');
	}
	public function indexApi(){
		$data=I('param.');
		$map['uid']=$data['uid'];
		if(isset($data['uid'])){
			$total=$this->creditorder->where($map)->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$orderlist=$this->creditorder->alias('a')->join('app_credit_goods as b ON a.gid=b.id')->field('a.id,a.num,a.totalcredit,a.date,b.thumbpic,b.title')->where($map)->order('a.date DESC')->limit($page->firstRow.','.$page->listRows)->select();
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
		if($data=$this->creditorder->create($data)){
			$oneUser=$this->user->field('credit')->where(array('id'=>$data['uid']))->find();
			if($oneUser['credit'] - $data['totalcredit'] < 0){
				$this->apiReturn(402,'用户积分不足');
			}
			$oneGoods=$this->creditgoods->field('inventory')->where(array('id'=>$data['gid']))->find();
			if($oneGoods['inventory']-$data['num'] < 0){
				$this->apiReturn(403,'商品库存不足');
			}
			if($this->creditorder->add($data)){
				if($data['gid'] && $data['uid']){
					$this->creditgoods->where(array('id'=>$data['gid']))->setDec('inventory',$data['num']);
					$this->user->where(array('id'=>$data['uid']))->seDec('credit',$data['totalcredit']);
				}
				$this->apiReturn(200,'兑换商品成功');
			}else{
				$this->apiReturn(404,'兑换商品失败');
			}
		}else{
			$this->apiReturn(401,$this->creditorder->getError());
		}
	}
}