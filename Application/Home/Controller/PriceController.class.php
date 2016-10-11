<?php
namespace Home\Controller;
use Think\Controller;

class PriceController extends Controller{
	private $price=null;
	public function __construct(){
		parent::__construct();
		$this->price=D('Price');
	}
	public function priceList(){
		$data=I('param.');
		$list=$this->price->field('minprice,maxprice')->where($data)->select();
		if($list){
			foreach($list as $value){
				$pricelist[]=$value['minprice'].'-'.$value['maxprice'];
			}
			$this->apiReturn(200,'返回价格区间成功',$pricelist);
		}else{
			$this->apiReturn(404,'暂无价格区间数据');
		}
	}
}