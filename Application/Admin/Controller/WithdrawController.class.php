<?php
namespace Admin\Controller;
use Think\Controller;

class WithdrawController extends BaseController{
	private $withdraw=null;
	public function __construct(){
		parent::__construct();
		$this->withdraw=D('Withdraw');
	}
	/**
	 * [index 提现申请列表]
	 * @return [type] [description]
	 */
	public function index(){
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->withdraw->delete($ids)){
					$this->success('删除成功！',U('Withdraw/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}
		}else{
			$total=$this->withdraw->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$drawlist=$this->withdraw->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,b.username,a.money,a.name,a.type,a.account,a.status,a.date')->order('a.date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('drawlist',$drawlist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	/**
	 * [del 删除提现记录]
	 * @return [type] [description]
	 */
	public function del(){
		$id=I('get.id');
		if($this->withdraw->delete($id)){
			$this->success('删除成功！',U('Withdraw/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
	/**
	 * [setStatus 设置提现申请状态]
	 */
	public function setStatus(){
		$data['id']=I('param.id');
		$oneDraw=$this->withdraw->where($data)->find();
		if($oneDraw['status'] == 1){
			$data['status'] = 0;
			if($this->withdraw->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->withdraw->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
}