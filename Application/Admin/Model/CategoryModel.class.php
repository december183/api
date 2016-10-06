<?php
namespace Admin\Model;
use Think\Model;

class CategoryModel extends Model{
	protected $_validate=array(
		array('name','require','栏目名称必须填写！'),
	);
	protected $_auto=array(
		array('sort','getAutoIncid',1,'callback'),
	);
	public function getAutoIncid(){
		$sql="SHOW TABLE STATUS LIKE 'app_category'";
		$res=$this->query($sql);
		return $res[0]['Auto_increment'];
	}
	public function getSortNav($param){
		$keyArr=$sortNav=$res=array();
		$data=$this->navSelectTree($param);
		//获取顶级栏目
		foreach($data as $key=>$value){
			if($value['level']==0){
				$keyArr[]=$key;
				$sortNav[]=$value;
			}
		}
		//将相应子栏目作为对应顶级栏目的child属性
		$length=count($data);
		foreach($sortNav as $k=>$v){
			$next=$keyArr[$k+1];
			$prev=$keyArr[$k];
			if($next-$prev > 1){
				$v['child']=array_slice($data,$prev+1,$next-$prev-1);
			}
			if($v == end($sortNav) && $prev != $length-1){
				$v['child']=array_slice($data,$prev+1);
			}
			$res[]=$v;
		}
		return $res;
	}
	public function navSelectTree($groupid){
		$map['type']=array('neq',0);
		$map['groupid']=array('eq',$groupid);
		$data=$this->field('id,pid,name')->where($map)->order('sort ASC')->select();
		return $this->resort($data);
	}
	private function resort($data,$pid=0,$level=0){
		static $res=array();
		foreach($data as $key=>$value){
			if($value['pid'] == $pid){
				$value['level']=$level;
				$res[]=$value;
				$this->resort($data,$value['id'],$level+1);
			}
		}
		return $res;
	}
	public function getAllSortNav($param){
		$map['groupid']=$param;
		$data=$this->where($map)->order('sort ASC')->select();
		return $this->resort($data);
	}
	public function getDelIds($param){
		$data=$this->select();
		$idArr=array();
		if(is_array($param)){
			foreach($param as $key=>$value){
				$childIdArr=$this->getChildId($data,$value);
				//合并获取的子类id数组
				$idArr=array_merge($idArr,$childIdArr);
			}
			//合并传入的id数组
			$idArr=array_merge($idArr,$param);
			//去除重复id
			$idArr=array_unique($idArr);
			$ids=implode(',',$idArr);
		}else{
			$idArr=$this->getChildId($data,$param);
			array_push($idArr,$param);//将传入的参数加入获取的数组中
			$ids=implode(',',$idArr);
		}
		return $ids;
	}
	protected function getChildId($data,$id){
		static $result=array();
		foreach($data as $key=>$value){
			if($value['pid']==$id){
				$result[]=$value['id'];
				$this->getChildId($data,$value['id']);
			}
		}
		return $result;
	}
	//获取包括当前栏目的所有上级栏目
	public function getAllPreNav($id){
		static $res=array();
		$map['id']=array('eq',$id);
		$data=$this->field('id,pid,name,type')->where($map)->find();
		$res[]=$data;
		if($data['pid'] != 0){
			$this->getAllPreNav($data['pid']);
		}
		return array_reverse($res);
	}
}