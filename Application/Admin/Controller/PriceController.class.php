<?php
namespace Admin\Controller;
use Think\Controller;

class PriceController extends BaseController{
	private $price=null;
	private $category=null;
	public function __construct(){
		parent::__construct();
		$this->price=D('Price');
		$this->category=D('Category');
	}
	public function index(){
		$param=I('get.');
		if($param){
			foreach($param as $key=>$value){
				$paramStr.=$key.'/'.$value;
			}
			$map=$param;
		}else{
			$paramStr='gid/1';
			$map=array('gid'=>1);
		}
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->price->delete($ids)){
					$this->success('删除成功！',U('Admin/Price/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}
		}else{
			$total=$this->price->where($map)->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$pricelist=$this->price->alias('a')->join('app_category as b ON a.cateid=b.id')->field('a.id,a.minprice,a.maxprice,b.name as catename')->where($map)->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('pricelist',$pricelist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($this->price->create($data)){
				if($this->price->add()){
					$this->success('新增价格区间成功！',U('Admin/Price/index/gid/'.$data['gid']),2);
				}else{
					$this->error('新增价格区间失败！');
				}
			}else{
				$this->error($this->price->getError());
			}
		}else{
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($this->price->create($data)){
				if($this->price->save()){
					$this->success('修改价格区间成功！',U('Admin/Price/index/gid/'.$data['gid']),2);
				}else{
					$this->error('修改价格区间失败！');
				}
			}else{
				$this->error($this->price->getError());
			}
		}else{
			$data['id']=I('get.id');
			$onePrice=$this->price->where($data)->find();
			$this->assign('onePrice',$onePrice);
			$this->display();
		}
	}
	public function del(){
		$id=I('get.id');
		$onePrice=$this->price->where(array('id'=>$id))->find();
		if($onePrice){
			if($this->price->delete($id)){
				$this->success('删除成功！',U('Admin/Price/index/gid/'.$onePrice['gid']),2);
			}else{
				$this->error('删除失败！');
			}
		}else{
			$this->error('未找到该记录');
		}
	}
}