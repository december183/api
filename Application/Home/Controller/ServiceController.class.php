<?php
namespace Home\Controller;
use Think\Controller;

class ServiceController extends ComController{
	private $service=null;
	private $admire=null;
	private $collect=null;
	private $category=null;
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->service=D('Service');
		$this->admire=D('Admire');
		$this->collect=D('Collect');
		$this->category=D('Category');
		$this->user=D('User');
	}
	public function serviceList(){
		$data=I('param.');
		// $map['cateid']=$data['cateid'];
		$map['cateid']=array('IN',$this->category->getDelIds($data['cateid']));
		$map['isup']=array('eq',1);
		$map['isdelete']=array('eq',0);
		if(isset($data['price'])){
			$priceArr=explode('-',$data['price']);
			$map['price']=array('gt',$priceArr[0]);
			$map['price']=array('lt',$priceArr[1]);
		}
		if(isset($data['region'])){
			$map['region']=array('eq',$data['region']);
		}
		if(isset($data['agerange'])){
			$map['agerange']=array('eq',$data['agerange']);
		}
		if(isset($data['keywords'])){
			$map['title']=array('like','%'.urlencode($data['keywords'].'%'));
		}
		$total=$this->service->where($map)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$show=$page->show();
		$list=$this->service->field('id,uid,thumbpic,title,price,location')->where($map)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
		$admirelist=$this->admire->field('serviceid')->where(array('uid'=>$data['uid'],'commentid'=>0))->select();
		foreach($admirelist as $value){
			$admireids .= $value['serviceid'].',';
		}
		$admireids=substr($admireids,0,-1);
		$collectlist=$this->collect->field('serviceid')->where(array('uid'=>$data['uid'],'eventid'=>0))->select();
		foreach($collectlist as $value){
			$collectids .= $value['serviceid'].',';
		}
		$collectids=substr($collectids,0,-1);
		foreach($list as $value){
			$oneUser=$this->user->field('username')->where(array('id'=>$value['uid']))->find();
			$value['username']=$oneUser['username'];
			if(strpos($admireids,$value['id']) !== false){
				$value['isadmire']=1;
			}else{
				$value['isadmire']=0;
			}
			if(strpos($collectids,$value['id']) !== false){
				$value['iscollect']=1;
			}else{
				$value['iscollect']=0;
			}
			$servicelist[]=$value;
		}
		if($servicelist){
			$this->apiReturn(200,'返回商品列表成功',$servicelist);
		}else{
			$this->apiReturn(401,'暂无数据');
		}
	}
	/**
	 * [releaseService IOS发布商品]
	 * @return [type] [description]
	 */
	public function releaseService(){
		$data=I('param.');
		if($_FILES['file']){
			$arr=$this->upload();
			foreach($arr as $key=>$path){
				$imgArr=getimagesize($path);
				if($imgArr[0] < 800 && $imgArr[1] < 800){
					$path=str_replace('\\', '/',$path);
					$data['mainpic'].=strstr($path,__ROOT__.'/Uploads/image/').';';
				}else{
					$data['mainpic'].=$this->thumb($path).';';
				}
				if($key == 0){
					$data['thumbpic']=$this->thumb($path,100,100);
				}
			}
			$data['mainpic']=substr($data['mainpic'],0,-1);
		}else{
			$this->apiReturn(402,'请上传商品主图');
		}
		if($this->service->create($data)){
			if($this->service->add()){
				$this->apiReturn(200,'商品发布成功');
			}else{
				$this->apiReturn(404,'商品发布失败');
			}
		}else{
			$this->apiReturn(403,$this->service->getError());
		}
	}
	/**
	 * [addService Andriod发布商品]
	 */
	public function addService(){
		$data=I('param.');
		if($data=$this->service->create($data)){
			if($this->service->add($data)){
				$this->apiReturn(200,'商品发布成功');
			}else{
				$this->apiReturn(404,'商品发布失败');
			}
		}else{
			$this->apiReturn(403,$this->service->getError());
		}
	}
	public function editService(){
		if(IS_POST){
			$data=I('param.');
			if($_FILES['file']){
				$arr=$this->upload();
				foreach($arr as $key=>$path){
					$imgArr=getimagesize($path);
					if($imgArr[0] < 800 && $imgArr[1] < 800){
						$path=str_replace('\\', '/',$path);
						$data['mainpic'].=strstr($path,__ROOT__.'/Uploads/image/').';';
					}else{
						$data['mainpic'].=$this->thumb($path).';';
					}
					if($key == 0){
						$data['thumbpic']=$this->thumb($path,100,100);
					}
				}
				$data['mainpic']=substr($data['mainpic'],0,-1);
			}
			if($this->service->create($data)){
				if($this->service->save()){
					$this->apiReturn(200,'商品修改成功');
				}else{
					$this->apiReturn(404,'商品修改失败');
				}
			}else{
				$this->apiReturn(402,$this->service->getError());
			}
		}else{
			$data=I('get.');
			$oneService=$this->service->where(array('id'=>$data['id']))->find();
			if($oneService){
				if($oneService['uid'] == $data['uid']){
					$this->apiReturn(200,'返回商品信息成功',$oneService);
				}else{
					$this->apiReturn(402,'无权限进行此操作');
				}
			}else{
				$this->apiReturn(404,'未找到该商品信息');
			}
		}
	}
	public function serviceDetail(){
		$data=I('param.');
		$map['a.id']=$data['id'];
		$oneService=$this->service->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.uid,a.title,a.mainpic,a.price,a.discountprice,a.location,a.phone,a.descript,b.shopname')->where($map)->find();
		if($oneService){
			$oneAdmire=$this->admire->where(array('serviceid'=>$data['id'],'uid'=>$data['uid']))->find();
			if($oneAdmire){
				$oneService['isadmire']=1;
			}else{
				$oneService['isadmire']=0;
			}
			$oneCollect=$this->collect->where(array('serviceid'=>$data['id'],'uid'=>$data['uid']))->find();
			if($oneCollect){
				$oneService['iscollect']=1;
			}else{
				$oneService['iscollect']=0;
			}
			$this->apiReturn(200,'返回商品详情成功',$oneService);
		}else{
			$this->apiReturn(404,'暂无该商品信息');
		}
	}
	public function userServiceList(){
		$data['uid']=I('param.uid');
		$data['isdelete']=0;
		$total=$this->service->where($data)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$servicelist=$this->service->field('id,title,price,discountprice,mainpic,thumbpic,location,isup')->where($data)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
		if($servicelist){
			$this->apiReturn(200,'返回用户商品列表成功',$servicelist);
		}else{
			$this->apiReturn(404,'暂无用户商品列表信息');
		}
	}
	public function deleteService(){
		$data=I('param.');
		$oneService=$this->service->field('id,uid')->where(array('id'=>$data['id']))->find();
		if($oneService){
			if($data['uid'] == $oneService['uid']){
				$data['isdelete']=0;
				if($this->service->save($data)){
					$this->apiReturn(200,'删除成功');
				}else{
					$this->apiReturn(404,'删除失败');
				}
			}else{
				$this->apiReturn(402,'无权限进行此操作');
			}
		}else{
			$this->apiReturn(401,'暂无该商品');
		}
	}
	public function isUpService(){
		$data=I('param.');
		$oneService=$this->service->field('id,uid,isup')->where(array('id'=>$data['id']))->find();
		if($oneService){
			if($data['uid'] == $oneService['uid']){
				$data2['id']=$data['id'];
				if($oneService['isup'] == 1){
					$data2['isup']=0;
					if($this->service->save($data2)){
						$this->apiReturn(200,'下架成功');
					}else{
						$this->apiReturn(404,'下架失败');
					}
				}else{
					$data2['isup']=1;
					if($this->service->save($data2)){
						$this->apiReturn(200,'上架成功');
					}else{
						$this->apiReturn(404,'上架失败');
					}
				}
			}else{
				$this->apiReturn(402,'无权限进行此操作');
			}
		}else{
			$this->apiReturn(401,'暂无该商品');
		}
	}
	public function userDiscountService(){
		$data=I('param.');
		$data['isdiscount']=1;
		$data['isup']=1;
		$data['status']=1;
		$data['isdelete']=0;
		$total=$this->service->where($data)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$servicelist=$this->service->field('id,title,thumbpic,price,discountprice,salednum')->where($data)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
		if($servicelist){
			$this->apiReturn(200,'返回商家优惠商品成功',$servicelist);
		}else{
			$this->apiReturn(404,'暂无商家优惠商品');
		}
	}
}