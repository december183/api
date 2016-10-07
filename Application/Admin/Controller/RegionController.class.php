<?php
namespace Admin\Controller;
use Think\Controller;

class RegionController extends BaseController{
	private $region=null;
	private $city=null;
	private $province=null;
	public function __construct(){
		parent::__construct();
		$this->region=D('Region');
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
				if($this->region->delete($idstr)){
					$this->success('删除成功！',U('Admin/Region/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_region SET sort='$value' WHERE id='$key'";
					$this->region->execute($sql);
				}
				$this->success('排序成功！',U('Admin/Region/index/'.$paramStr),2);
			}elseif($data['action'] == 'search'){
				$map['name']=array('like','%'.$data['q'].'%');
				$total=$this->region->where($map)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$regionlist=$this->region->alias('a')->join('app_province as b ON a.pid=b.id')->join('app_city as c ON a.cityid=c.id')->field('a.id,a.name,a.sort,b.name as province,c.name as city')->where($map)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
				$this->assign('regionlist',$regionlist);
				$this->display();
			}
		}else{
			$total=$this->region->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$regionlist=$this->region->alias('a')->join('app_province as b ON a.pid=b.id')->join('app_city as c ON a.cityid=c.id')->field('a.id,a.name,a.sort,b.name as province,c.name as city')->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('regionlist',$regionlist);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($this->region->create($data)){
				if($this->region->add()){
					$this->success('新增区域成功！',U('Region/index'),2);
				}else{
					$this->error('新增区域失败！');
				}
			}else{
				$this->error($this->region->getError());
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
			if($this->region->create($data)){
				if($this->region->save()){
					$this->success('修改区域成功！',U('Region/index'),2);
				}else{
					$this->error('修改区域失败！');
				}
			}else{
				$this->error($this->region->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneRegion=$this->region->where($data)->find();
			$provincelist=$this->province->order('sort ASC')->select();
			$this->assign('provincelist',$provincelist);
			$this->assign('oneRegion',$oneRegion);
			$this->display();
		}
	}
	public function del(){
		$data['id']=I('param.id');
		if($this->region->delete($data['id'])){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
	public function getRegionList(){
		$data['cityid']=I('param.cityid');
		$regionlist=$this->region->where($data)->select();
		if($regionlist){
			$response=array('errno'=>0,'list'=>$regionlist);
		}else{
			$response=array('errno'=>1,'errmsg'=>'未找到相关地区数据');
		}
		$this->ajaxReturn($response,'JSON');
	}
}