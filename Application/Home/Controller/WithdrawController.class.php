<?php
namespace Home\Controller;
use Think\Controller;

class WithdrawController extends Controller{
	private $withdraw=null;
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->withdraw=D('Withdraw');
		$this->user=D('User');
	}
	/**
	 * [indexApi 获取用户提现记录]
	 * @return [type] [description]
	 */
	public function indexApi(){
		$data['uid']=I('param.uid');
		if(isset($data['uid'])){
			$total=$this->withdraw->where($data)->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$drawlist=$this->withdraw->field('money,name,type,account,status,date')->where($data)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			if($drawlist){
				$this->apiReturn(200,'返回提现记录成功',$drawlist);
			}else{
				$this->apiReturn(404,'暂无用户提现记录');
			}
		}else{
			$this->apiReturn(401,'参数错误,需传入用户ID');
		}
	}
	/**
	 * [addApi 提现申请]
	 */
	public function addApi(){
		$data=I('param.');
		if($data=$this->withdraw->create($data)){
			$oneUser=$this->user->field('account')->where(array('id'=>$data['uid']))->find();
			if($data['money'] < 50){
				$this->apiReturn(402,'提现金额必须大于50');
			}
			if($oneUser['account'] < $data['money']){
				$this->apiReturn(403,'账户金额不足');
			}
			if($this->withdraw->add()){
				$this->user->where(array('id'=>$data['uid']))->setDec('account',$data['money']);
				$this->apiReturn(200,'提现申请成功');
			}else{
				$this->apiReturn(404,'提现申请失败');
			}
		}else{
			$this->apiReturn(401,$this->withdraw->getError());
		}
	}
}