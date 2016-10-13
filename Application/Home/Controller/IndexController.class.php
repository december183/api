<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	private $category=null;
	private $adver=null;
	private $user=null;
    private $price=null;
	public function __construct(){
		parent::__construct();
		$this->category=D('Category');
		$this->adver=D('Adver');
		$this->user=D('User');
        $this->price=D('Price');
	}
    public function indexApi(){
        $list=$this->category->field('id,name,thumb')->where(array('groupid'=>3,'pid'=>0))->order('sort ASC')->select();
        foreach($list as $value){
            $catepricelist=$this->price->field('minprice,maxprice')->where(array('cateid'=>$value['id'],'gid'=>2))->select();
            $pricelist=array();
            foreach($catepricelist as $val){
                $pricelist[]=$val['minprice'].'-'.$val['maxprice'];
            }
            $value['price']=$pricelist;
            $catelist[]=$value;
        }
        $bannerlist=$this->adver->field('id,title,type,url,thumb')->where(array('typeid'=>1,'status'=>1))->order('sort ASC')->limit(4)->select();
        $data['id']=I('param.id');
    	$oneUser=$this->user->field('birthday')->where($data)->find();
    	$agerange=getAgeRange($oneUser['birthday']);
    	if($agerange){
    		$map1=array('agerange'=>$agerange,'typeid'=>2,'status'=>1);
    		$map2=array('agerange'=>$agerange,'typeid'=>3,'status'=>1);
    		$map3=array('agerange'=>$agerange,'typeid'=>4,'status'=>1);
    		$map4=array('agerange'=>$agerange,'typeid'=>5,'status'=>1);
    		$map5=array('agerange'=>$agerange,'typeid'=>6,'status'=>1);
    	}else{
    		$map1=array('typeid'=>2,'status'=>1);
    		$map2=array('typeid'=>3,'status'=>1);
    		$map3=array('typeid'=>4,'status'=>1);
    		$map4=array('typeid'=>5,'status'=>1);
    		$map5=array('typeid'=>6,'status'=>1);
    	}
		$list1=$this->adver->field('id,title,type,url,thumb')->where($map1)->order('date DESC')->limit(1)->select();
    	$list2=$this->adver->field('id,title,type,url,thumb')->where($map2)->order('date DESC')->limit(1)->select();
    	$list3=$this->adver->field('id,title,type,url,thumb')->where($map3)->order('date DESC')->limit(2)->select();
    	$babyadverlist=array($list1,$list2,$list3);
    	$list4=$this->adver->field('id,title,type,url,thumb')->where($map1)->order('date DESC')->limit(1)->select();
    	$list5=$this->adver->field('id,title,type,url,thumb')->where($map2)->order('date DESC')->limit(1)->select();
    	$mamaadverlist=array($list4,$list5);
    	$data=array('cate'=>$catelist,'banner'=>$bannerlist,'baby'=>$babyadverlist,'mama'=>$mamaadverlist);
    	if($data){
    		$this->apiReturn(200,'首页数据返回成功',$data);
    	}else{
    		$this->apiReturn(404,'暂无首页相关数据');
    	}
    }
    public function cateList(){
    	$catelist=$this->category->field('id,name,thumb')->where(array('groupid'=>3,'pid'=>0))->order('sort ASC')->limit(8)->select();
    	if($catelist){
    		$this->apiReturn(200,'首页栏目数据返回成功',$catelist);
    	}else{
    		$this->apiReturn(404,'暂无相关栏目信息');
    	}
    }
    public function bannerList(){
    	$bannerlist=$this->adver->field('id,title,type,url,thumb')->where(array('typeid'=>1,'status'=>1))->order('date DESC')->limit(4)->select();
    	if($bannerlist){
    		$this->apiReturn(200,'首页banner数据返回成功',$bannerlist);
    	}else{
    		$this->apiReturn(404,'暂无首页banner数据');
    	}
    }
    public function custBabyAdver(){
    	$data['id']=I('param.id');
    	$oneUser=$this->user->field('birthday')->where($data)->find();
    	$agerange=getAgeRange($oneUser['birthday']);
    	if($agerange){
    		$map1=array('agerange'=>$agerange,'typeid'=>2,'status'=>1);
    		$map2=array('agerange'=>$agerange,'typeid'=>3,'status'=>1);
    		$map3=array('agerange'=>$agerange,'typeid'=>4,'status'=>1);
    	}else{
    		$map1=array('typeid'=>2,'status'=>1);
    		$map2=array('typeid'=>3,'status'=>1);
    		$map3=array('typeid'=>4,'status'=>1);
    	}
		$list1=$this->adver->field('id,title,type,url,thumb')->where($map1)->order('date DESC')->limit(1)->select();
    	$list2=$this->adver->field('id,title,type,url,thumb')->where($map2)->order('date DESC')->limit(1)->select();
    	$list3=$this->adver->field('id,title,type,url,thumb')->where($map3)->order('date DESC')->limit(2)->select();
    	$adverlist=array($list1,$list2,$list3);
    	if($adverlist){
    		$this->apiReturn(200,'返回宝宝自定义推荐广告成功',$adverlist);
    	}else{
    		$this->apiReturn(404,'暂无宝宝自定义推荐广告信息');
    	}
    }
    public function custMomAdver(){
    	$data['id']=I('param.id');
    	$oneUser=$this->user->field('birthday')->where($data)->find();
    	$agerange=getAgeRange($oneUser['birthday']);
    	if($agerange){
    		$map1=array('agerange'=>$agerange,'typeid'=>5,'status'=>1);
    		$map2=array('agerange'=>$agerange,'typeid'=>6,'status'=>1);
    	}else{
    		$map1=array('typeid'=>5,'status'=>1);
    		$map2=array('typeid'=>6,'status'=>1);
    	}
		$list1=$this->adver->field('id,title,type,url,thumb')->where($map1)->order('date DESC')->limit(1)->select();
    	$list2=$this->adver->field('id,title,type,url,thumb')->where($map2)->order('date DESC')->limit(1)->select();
    	$adverlist=array($list1,$list2);
    	if($adverlist){
    		$this->apiReturn(200,'返回妈妈自定义推荐广告成功',$adverlist);
    	}else{
    		$this->apiReturn(404,'暂无妈妈自定义推荐广告信息');
    	}
    }
}