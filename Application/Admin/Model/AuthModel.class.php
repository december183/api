<?php
namespace Admin\Model;
use Think\Model;

class AuthModel extends Model{
	protected $_validate=array(
		array('name','require','菜单名称必须填写！'),
	);
	protected $_auto=array(
		
	);
	public function getAllSortAuth(){
		$data=$this->select();
		return $this->resort($data);
	}
	
	public function getAllResortAuth(){
		$keyArr=$sortNav=$res=array();
		$data2=$this->order('sort ASC')->select();
		$data=$this->resort($data2);
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
	public function getMenu(){
		$keyArr=$sortNav=$res=array();
		$data2=$this->where(array('status'=>1))->order('sort ASC')->select();
		$data=$this->resortMenu($data2);
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
	private function resort($data,$pid=0,$level=0){
		static $res=array();
		foreach($data as $key=>$value){
			if($value['pid'] == $pid){
				$value['level']=$level;
				$res[]=$value;
				self::resort($data,$value['id'],$level+1);
			}
		}
		return $res;
	}
	static public function resortMenu($data,$pid=0,$level=0){
		$res=array();
		foreach($data as $key=>$value){
			if($value['pid'] == $pid){
				$value['level']=$level;
				$res[]=$value;
				$res=array_merge($res,self::resortMenu($data,$value['id'],$level+1));
			}
		}
		return $res;
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
				self::getChildId($data,$value['id']);
			}
		}
		return $result;
	}
}