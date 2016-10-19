<?php
namespace Admin\Controller;
use Think\Controller;

class CreditOrderController extends BaseController{
	private $creditorder=null;
	private $creditgoods=null;
	public function __construct(){
		parent::__construct();
		$this->creditorder=D('CreditOrder');
		$this->creditgoods=D('CreditGoods');
	}
	public function index(){
		if(IS_POST){
			$data=I('param.');
			$param=I('get.');
			if($param){
				foreach($param as $key=>$value){
					$paramStr.=$key.'/'.$value;
				}
			}
			if($data['action'] == 'delete'){
				$ids=implode(',',$data['id']);
				if($this->creditorder->delete($ids)){
					$this->success('批量删除成功！',U('Admin/CreditOrder/index/'.$paramStr),2);
				}else{
					$this->error('批量删除失败！');
				}
			}elseif($data['action'] == 'send'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_credit_order SET status=1 WHERE id='$id' LIMIT 1";
					$this->creditorder->execute($sql);
				}
				$this->success('批量发货成功！',U('Admin/CreditOrder/index/'.$paramStr),2);
			}elseif($data['action'] == 'search'){
				$map['title']=array('like','%'.$data['q'].'%');
				$goodslist=$this->creditgoods->field('id')->where($map)->select();
				foreach($goodslist as $value){
					$ids.=$value['id'].',';
				}
				$ids=substr($ids,0,-1);
				if($ids){
					$condition['gid']=array('in',$ids);
				}else{
					$condition['gid']=array('eq',$ids);
				}
				$total=$this->creditorder->where($condition)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$orderlist=$this->creditorder->alias('a')->join('app_credit_goods as b ON a.gid=b.id')->field('a.id,a.num,a.credit,a.totalcredit,a.pickaddress,a.remark,a.status,b.title,b.thumbpic')->where($condition)->order('a.date DESC')->limit($page->firstRow.','.$page->listRows)->select();
				$this->assign('page',$show);
				$this->assign('orderlist',$orderlist);
				$this->display();
			}
		}else{
			$total=$this->creditorder->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$orderlist=$this->creditorder->alias('a')->join('app_credit_goods as b ON a.gid=b.id')->field('a.id,a.num,a.credit,a.totalcredit,a.pickaddress,a.remark,a.status,b.title,b.thumbpic')->order('a.date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('orderlist',$orderlist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	public function del(){
		$id=I('get.id');
		if($this->creditorder->delete($id)){
			$this->success('删除成功！',U('CreditOrder/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneOder=$this->creditorder->field('id,status')->where($data)->find();
		if($oneOder['status'] == 0){
			$data['status'] = 1;
			if($this->creditorder->save($data)){
				$response=array('errno'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$response=array('errno'=>1);
		}
		$this->ajaxReturn($response,'json');
	}
}