<?php
namespace Admin\Controller;
use Think\Controller;

class CityController extends BaseController{
	private $city=null;
	private $province=null;
	public function __construct(){
		parent::__construct();
		$this->city=D('City');
		$this->province=D('Province');
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
			if($data['action']=='delete'){
				$idstr=implode(',',$data['id']);
				if($this->city->delete($idstr)){
					$this->success('删除成功！',U('Admin/City/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_city SET sort='$value' WHERE id='$key'";
					$this->city->execute($sql);
				}
				$this->success('排序成功！',U('Admin/City/index/'.$paramStr),2);
			}elseif($data['action'] == 'search'){
				$map['name']=array('like','%'.$data['q'].'%');
				$total=$this->city->where($map)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$citylist=$this->city->alias('a')->join('app_province as b ON a.pid=b.id')->field('a.id,a.name,a.sort,b.name as province')->where($map)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
				$this->assign('citylist',$citylist);
				$this->display();
			}
		}else{
			$total=$this->city->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$citylist=$this->city->alias('a')->join('app_province as b ON a.pid=b.id')->field('a.id,a.name,a.sort,b.name as province')->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('citylist',$citylist);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($this->city->create($data)){
				if($this->city->add()){
					$this->success('新增城市成功！',U('City/index'),2);
				}else{
					$this->error('新增城市失败！');
				}
			}else{
				$this->error($this->city->getError());
			}
		}else{
			$provincelist=$this->province->order('sort ASC')->select();
			$this->assign('provincelist',$provincelist);
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($this->city->create($data)){
				if($this->city->save()){
					$this->success('修改城市成功！',U('City/index'),2);
				}else{
					$this->error('修改城市失败！');
				}
			}else{
				$this->error($this->city->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneCity=$this->city->where($data)->find();
			$provincelist=$this->province->order('sort ASC')->select();
			$this->assign('provincelist',$provincelist);
			$this->assign('oneCity',$oneCity);
			$this->display();
		}
	}
	public function del(){
		$data['id']=I('param.id');
		if($this->city->delete($data['id'])){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
	public function getCityList(){
		$data['pid']=I('param.pid');
		$citylist=$this->city->field('id,name')->where($data)->order('sort ASC')->select();
		if($citylist){
			$response=array('errno'=>0,'list'=>$citylist);
		}else{
			$response=array('errno'=>1,'errmsg'=>'未找到相关城市数据');
		}
		$this->ajaxReturn($response,'JSON');
	}
}