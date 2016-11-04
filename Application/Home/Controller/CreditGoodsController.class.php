<?php
namespace Home\Controller;
use Think\Controller;

class CreditGoodsController extends Controller{
	private $creditgoods=null;
	private $daily=null;
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->creditgoods=D('CreditGoods');
		$this->daily=D('Daily');
		$this->user=D('User');
	}
	public function indexApi(){
		$data=I('param.');
		$condition['uid']=$data['uid'];
		if(isset($data['uid'])){
			$oneDaily=$this->daily->alias('a')->join('LEFT JOIN __USER__ as b ON b.id=a.uid')->field('a.issigned,a.signcount,a.date,b.username,b.avatar,b.credit')->where($condition)->order('a.date DESC')->limit(1)->select();
			if($oneDaily){
				$date=date('Y-m-d',$oneDaily[0]['date']);
				$today=date('Y-m-d',time());
				if($date == $today && $oneDaily[0]['issigned'] == 1){
					$oneDaily[0]['issigned']=1;
				}else{
					$oneDaily[0]['issigned']=0;
				}
				$daily=$oneDaily[0];
			}else{
				$oneUser=$this->user->field('username,avatar,credit')->where(array('id'=>$data['uid']))->find();
				$oneUser['issigned']=0;
				$oneUser['signcount']=0;
				$daily=$oneUser;
			}
			$map=array('isrec'=>1);
			$total=$this->creditgoods->where($map)->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$goodslist=$this->creditgoods->field('id,title,credit,thumbpic')->where($map)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			$data=array('daily'=>$daily,'goods'=>$goodslist);
			if($data){
				$this->apiReturn(200,'返回积分商城商品成功',$data);
			}else{
				$this->apiReturn(404,'返回积分商城商品失败');
			}
		}else{
			$this->apiReturn(401,'必须传入当前登陆用户ID');
		}
	}
	public function allApi(){
		$total=$this->creditgoods->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$goodslist=$this->creditgoods->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
		if($goodslist){
			$this->apiReturn(200,'返回积分商品列表成功',$goodslist);
		}else{
			$this->apiReturn(404,'暂无积分商品信息');
		}
	}
	public function canApi(){
		$data=I('param.');
		if(isset($data['uid'])){
			$oneUser=$this->user->field('credit')->where(array('id'=>$data['uid']))->find();
			$map['credit']=array('lt',$oneUser['credit']);
			$total=$this->creditgoods->where($map)->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$goodslist=$this->creditgoods->field('id,thumbpic,title,inventory,exchangednum,credit')->where($map)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			if($goodslist){
				$this->apiReturn(200,'返回积分商品列表成功',$goodslist);
			}else{
				$this->apiReturn(404,'暂无积分商品信息');
			}
		}else{
			$this->apiReturn(401,'必须传入当前登陆用户ID');
		}
	}
	public function detailApi(){
		$data=I('param.');
		if(isset($data['id'])){
			$map['id']=$data['id'];
			$oneGoods=$this->creditgoods->field('id,title,mainpic,credit,inventory,content')->where($map)->select();
			if($oneGoods){
				$this->apiReturn(200,'返回积分商品信息成功',$oneGoods);
			}else{
				$this->apiReturn(404,'未找到该商品信息');
			}
		}else{
			$this->apiReturn(401,'参数错误，需传入商品ID');
		}
	}
}