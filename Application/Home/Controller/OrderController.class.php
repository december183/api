<?php
namespace Home\Controller;
use Think\Controller;

class OrderController extends ComController{
	private $order=null;
	private $goods=null;
	private $service=null;
	private $user=null;
	private $pick=null;
	public function __construct(){
		parent::__construct();
		$this->order=D('Order');
		$this->goods=D('Goods');
		$this->service=D('Service');
		$this->user=D('User');
		$this->pick=D('Pickaddress');
	}
	/**
	 * [addApi 新增订单]
	 */
	public function addApi(){
		$data=I('param.');
		$data['order_no']=getTradeNo($data['type']);
		$data['info']=stripslashes(htmlspecialchars_decode($data['info']));
		// $this->apiReturn(101,'调试',$data);
		$data['info']=json_decode($data['info'],true);
		$body='';
		foreach($data['info'] as $key=>$value){
			if($value['type'] == 1){
				$oneGoods=$this->service->field('title,price,discountprice')->where(array('id'=>$value['id']))->find();
			}else{
				$oneGoods=$this->goods->field('title,price,discountprice')->where(array('id'=>$value['id']))->find();
			}
			if($oneGoods['discountprice'] != 0){
				$data['totalfee']+=$oneGoods['discountprice']*$value['num'];
				$value['price']=$oneGoods['discountprice'];
			}else{
				$data['totalfee']+=$oneGoods['price']*$value['num'];
				$value['price']=$oneGoods['price'];
			}
			unset($data['info'][$key]);
			$data['info'][]=$value;
			$body.=$oneGoods['title'].';';
		}
		$data['info']=json_encode($data['info']);
		$params=$data;
		$params['body']=substr($body,0,-1);
		if($this->order->create($data)){
			if($insertId=$this->order->add()){
				if($data['paymethod'] == 1){
					$response=$this->aliPayApi($params);
				}elseif($data['paymethod'] == 2){
					$response=$this->wxPayApi($params);
					$response['id']=$insertId;
				}else{
					
				}
				$this->apiReturn(200,'生成订单成功',$response);
			}else{
				$this->apiReturn(404,'生成订单失败');
			}
		}else{
			$this->apiReturn(401,$this->order->getError());
		}
	}
	/**
	 * [payApi 立即支付]
	 * @return [type] [description]
	 */
	public function payApi(){
		$data=I('param.');
		$map['id']=$data['id'];
		$oneOrder=$this->order->field('id,uid,order_no,info,totalfee,pickid,paymethod,remark')->where($map)->find();
		if($oneOrder){
			$oneOrder['info']=json_decode($oneOrder['info'],true);
			foreach($oneOrder['info'] as $value){
				if($value['type'] == 1){
					$oneGoods=$this->service->field('title')->where(array('id'=>$value['id']))->find();
				}else{
					$oneGoods=$this->goods->field('title')->where(array('id'=>$value['id']))->find();
				}
				$oneGoods['num']=$value['num'];
				$oneGoods['price']=$value['price'];
				$oneGoods['type']=$value['type'];
				$oneOrder['detail'][]=$oneGoods;
				$oneOrder['body'].=$oneGoods['title'].';';
			}
			// unset($oneOrder['info']);
			$oneOrder['body']=substr($oneOrder['body'], 0 ,-1);
			if(IS_POST){
				$oneOrder['client_ip']=$data['client_ip'];
				if($data=$this->order->create($data)){
					if($data['paymethod'] == 1){
						if(($data['pickid'] != $oneOrder['pickid']) || ($data['paymethod'] != $oneOrder['paymethod'])){
							$this->order->save($data);
						}
						$response=$this->aliPayApi($oneOrder);
					}elseif($data['paymethod'] == 2){				
						$this->order->where(array('id'=>$data['id']))->setField('status',0);
						$data2['order_no']=getTradeNo($data['type']);
						$data2['uid']=$oneOrder['uid'];
						$data2['info']=json_encode($oneOrder['info']);
						$data2['totalfee']=$oneOrder['totalfee'];
						$data2['pickid']=$data['pickid'];
						$data2['paymethod']=$data['paymethod'];
						$data2['remark']=$oneOrder['remark'];
						$data2['date']=time();
						// $this->apiReturn(101,'调试',$data2);
						if($insertId=$this->order->add($data2)){
							$oneOrder['order_no']=$data2['order_no'];
							$response=$this->wxPayApi($oneOrder);
							$response['id']=$insertId;
						}else{
							$this->apiReturn('404','重新生成订单失败');
						}
					}else{
						
					}
					$this->apiReturn(200,'返回订单信息成功',$response);
				}else{
					$this->apiReturn(401,$this->order->getError());
				}
			}else{
				$this->apiReturn(200,'返回订单信息成功',$oneOrder);
			}
		}else{
			$this->apiReturn(404,'暂无该订单信息');
		}
	}
	/**
	 * [addApi Andriod新增订单]
	 */
	/*public function addApi2(){
		$data=I('param.');
		$data['order_no']=getTradeNo($data['type']);
		$data['info']=htmlspecialchars_decode($data['info']);
		// $this->apiReturn(101,'调试',$data);
		$data['info']=json_decode($data['info'],true);
		
		$body='';
		foreach($data['info'] as $key=>$value){
			if($value['type'] == 1){
				$oneGoods=$this->service->field('title,price,discountprice')->where(array('id'=>$value['id']))->find();
			}else{
				$oneGoods=$this->goods->field('title,price,discountprice')->where(array('id'=>$value['id']))->find();
			}
			if($oneGoods['discountprice'] != 0){
				$data['totalfee']+=$oneGoods['discountprice']*$value['num'];
				$value['price']=$oneGoods['discountprice'];
			}else{
				$data['totalfee']+=$oneGoods['price']*$value['num'];
				$value['price']=$oneGoods['price'];
			}
			unset($data['info'][$key]);
			$data['info'][]=$value;
			$body.=$oneGoods['title'].';';
		}
		$data['info']=json_encode($data['info']);
		$params=$data;
		$params['body']=substr($body,0,-1);

		if($this->order->create($data)){
			if($this->order->add()){
				if($data['paymethod'] == 1){
					$response=$this->aliPayApi($params);
				}elseif($data['paymethod'] == 2){
					$response=$this->wxPayApi($params);
				}else{

				}
				$this->apiReturn(200,'生成订单成功',$response);
			}else{
				$this->apiReturn(404,'生成订单失败');
			}
		}else{
			$this->apiReturn(401,$this->order->getError());
		}
	}*/
	/**
	 * [userIndexApi 用户订单列表]
	 * @return [type] [description]
	 */
	public function userIndexApi(){
		$data['uid']=I('param.uid');
		if(isset($data['uid'])){
			$orderlist=$this->getOrderList($data);
			if($orderlist){
				$this->apiReturn(200,'返回商家订单信息成功',$orderlist);
			}else{
				$this->apiReturn(404,'暂无商家订单信息');
			}
		}else{
			$this->apiReturn(401,'参数错误,需传入当前登陆用户ID');
		}
	}
	/**
	 * [sellerIndexApi 商家订单列表]
	 * @return [type] [description]
	 */
	public function sellerIndexApi(){
		$data['uid']=I('param.uid');
		if(isset($data['uid'])){
			$servicelist=$this->service->field('id')->where($data)->select();
			foreach($servicelist as $value){
				$serviceids.=$value['id'].',';
			}
			$serviceids=substr($serviceids, 0, -1);
			$goodslist=$this->goods->field('id')->where($data)->select();
			foreach($goodslist as $value){
				$goodsids.=$value['id'].',';
			}
			$goodsids=substr($goodsids, 0, -1);
			$list=$this->order->field('id,info,totalfee,status,date')->select();
			$orderlist=array();
			foreach($list as $value){
				$value['info']=json_decode($value['info'],true);
				foreach($value['info'] as $val){
					if((strpos($serviceids,$val['id']) !== false) || (strpos($goodsids,$val['id']) !== false)){
						$idsArr[]=$value['id'];
					}
				}
			}
			array_unique($idsArr);
			$ids=implode(',',$idsArr);
			if($ids){
				$map['id']=array('in',$ids);
			}else{
				$map['id']=array('eq',$ids);
			}
			$orderlist=$this->getOrderList($map);
			if($orderlist){
				$this->apiReturn(200,'返回商家订单信息成功',$orderlist);
			}else{
				$this->apiReturn(404,'暂无商家订单信息');
			}
		}else{
			$this->apiReturn(401,'参数错误,需传入当前登陆用户ID');
		}
	}
	/**
	 * [getOrderList 获取订单列表信息]
	 * @param  [type] $param [description]
	 * @return [type]        [description]
	 */
	protected function getOrderList($param){
		$total=$this->order->where($param)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$list=$this->order->field('id,order_no,info,totalfee,status,date')->where($param)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
		if($list){
			$orderlist=array();
			foreach($list as $value){
				$value['info']=json_decode($value['info'],true);
				$value['detail']=array();
				foreach($value['info'] as $val){
					if($val['type'] == 1){
						$oneGoods=$this->service->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.title,a.thumbpic,a.location,b.shopname')->where(array('a.id'=>$val['id']))->find();
					}else{
						$oneGoods=$this->goods->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.title,a.thumbpic,a.sn,b.username')->where(array('a.id'=>$val['id']))->find();
					}
					$oneGoods['num']=$val['num'];
					$oneGoods['type']=$val['type'];
					$oneGoods['price']=$val['price'];
					$value['detail'][]=$oneGoods;
				}
				unset($value['info']);
				$orderlist[]=$value;
			}
			return $orderlist;
		}else{
			return false;
		}
	}
	/**
	 * [notify_url 微信回调方法]
	 * @return [type] [description]
	 */
	public function wxNotifyUrl(){
		$dataXml=$GLOBALS['HTTP_RAW_POST_DATA'];
		$data=xmlToArray($dataXml);
		if($data['return_code'] == 'SUCCESS'){
			if($data['result_code'] == 'SUCCESS'){
				$oneOrder=$this->order->field('id,status,info')->where(array('order_no'=>$data['out_trade_no']))->find();
				if($oneOrder && ($oneOrder['status'] == 4)){
					$data2['id']=$oneOrder['id'];
					$data2['status']=3;
					if($this->order->save($data2)){
						$info=json_decode($oneOrder['info'],true);
						foreach($info as $value){
							if($value['type'] == 1){
								$oneGoods=$this->service->field('uid')->where(array('id'=>$value['id']))->find();
							}elseif($value['type'] == 2){
								$oneGoods=$this->goods->field('uid')->where(array('id'=>$value['id']))->find();
							}
							$fee=$value['num']*$value['price'];
							$this->user->where(array('id'=>$oneGoods['uid']))->setInc('account',$fee);
						}
						$retArr=array('return_code'=>'SUCCESS','return_msg'=>'OK');
					}else{
						$retArr=array('return_code'=>'FAIL','return_msg'=>'后台处理失败');
					}
				}else{
					$retArr=array('return_code'=>'FAIL','return_msg'=>'系统未找到订单信息');
				}
			}else{
				$retArr=array('return_code'=>'FAIL','return_msg'=>'交易失败');
			}
		}else{
			$retArr=array('return_code'=>'FAIL','return_msg'=>'签名失败');
		}
		return arrayToXml($retArr);
	}
	/**
	 * [notify_url 支付宝回调方法]
	 * @return [type] [description]
	 */
	public function aliNotifyUrl2(){
		$public_key='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRA
FljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQE
B/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5Ksi
NG9zpgmLCUYuLkxpLQIDAQAB
-----END PUBLIC KEY-----';
		$retStr=urldecode($_SERVER['QUERY_STRING']);
		$data=explode('&',$retStr);
		$sign=base64_decode($data['sign']);
		unset($data['sign']);
		unset($data['sign_type']);
		ksort($data);
		$tempSign=toUrlParam($data);
		$pu_key=openssl_pkey_get_public($public_key);
		$result=openssl_verify($tempSign,$sign,$pu_key);
		if($result){
			$oneOrder=$this->order->field('id,status,totalfee')->where(array('order_no'=>$data['out_trade_no']))->find();
			if($oneOrder && ($oneOrder['status'] == 4)){
				$data2['id']=$oneOrder['id'];
				$data2['status']=3;
				if($this->order->save($data2)){
					echo "success";
				}else{
					echo 'failure';
				}
			}else{
				echo 'failure';
			}
		}else{
			echo 'failure';
		}
	}
	/**
	 * [notify_url IOS支付宝回调方法]
	 * @return [type] [description]
	 */
	public function aliNotifyUrl(){
		$data=I('param.');
		$oneOrder=$this->order->field('id,info,status,totalfee')->where(array('order_no'=>$data['out_trade_no']))->find();
		if($oneOrder){
			if(($oneOrder['status'] == 4) && ($oneOrder['totalfee'] == $data['buyer_pay_amount']) && ($oneOrder['totalfee'] == $data['receipt_amount'])){
				$data2['id']=$oneOrder['id'];
				$data2['status']=3;
				if($this->order->save($data2)){
					$info=json_decode($oneOrder['info'],true);
					foreach($info as $value){
						if($value['type'] == 1){
							$oneGoods=$this->service->field('uid')->where(array('id'=>$value['id']))->find();
						}elseif($value['type'] == 2){
							$oneGoods=$this->goods->field('uid')->where(array('id'=>$value['id']))->find();
						}
						$fee=$value['num']*$value['price'];
						$this->user->where(array('id'=>$oneGoods['uid']))->setInc('account',$fee);
					}
					echo "success";
				}else{
					echo 'failure';
				}
			}else{
				echo 'failure';
			}
		}else{
			echo 'failure';
		}
	}
	/**
	 * [aliReturnApi Andriod支付宝支付完成客户端回调接口]
	 * @return [type] [description]
	 */
	public function aliReturnApi(){
		$data=I('param.');
		if(isset($data['out_trade_no'])){
			$map['order_no']=$data['out_trade_no'];
			$oneOrder=$this->order->field('id,order_no,uid,info,totalfee,paymethod,pickid,status,date')->where($map)->find();
			if($oneOrder){
				if($data['resultStatus'] == 9000){
					$this->order->where(array('id'=>$oneOrder['id']))->setField('status',3);
					$info=json_decode($oneOrder['info'],true);
					foreach($info as $value){
						if($value['type'] == 1){
							$oneGoods=$this->service->field('uid')->where(array('id'=>$value['id']))->find();
						}elseif($value['type'] == 2){
							$oneGoods=$this->goods->field('uid')->where(array('id'=>$value['id']))->find();
						}
						$fee=$value['num']*$value['price'];
						$this->user->where(array('id'=>$oneGoods['uid']))->setInc('account',$fee);
					}
					$oneOrder['status']=3;
				}
				$onePick=$this->pick->field('name,phone,place')->where(array('id'=>$oneOrder['pickid']))->find();
				$oneOrder['pick']=$onePick;
				$oneOrder['info']=json_decode($oneOrder['info'],true);
				$oneOrder['detail']=array();
				foreach($oneOrder['info'] as $value){
					if($value['type'] == 1){
						$oneGoods=$this->service->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.thumbpic,a.title,b.id as sellerid,b.shopname,b.phone as sellerphone')->where(array('a.id'=>$value['id']))->find();
					}else{
						$oneGoods=$this->goods->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.thumbpic,a.title,b.id as sellerid,b.username,b.phone as sellerphone')->where(array('a.id'=>$value['id']))->find();
					}
					$oneGoods['num']=$value['num'];
					$oneGoods['type']=$value['type'];
					$oneGoods['price']=$value['price'];
					$oneOrder['detail'][]=$oneGoods;
				}
				unset($oneOrder['pickid']);
				unset($oneOrder['info']);
				$this->apiReturn(200,'返回订单详情成功',$oneOrder);
			}else{
				$this->apiReturn(404,'暂无该订单信息');
			}
		}else{
			$this->apiReturn(401,'参数错误,需传入订单号');
		}
	}
	/**
	 * [cancelApi 取消订单]
	 * @return [type] [description]
	 */
	public function cancelApi(){
		$data['id']=I('param.id');
		$data['uid']=I('param.uid');
		if(isset($data['id']) && isset($data['uid'])){
			$oneOrder=$this->order->field('id,status')->where($data)->find();
			if($oneOrder){
				if($oneOrder['status'] == 4){
					$data['status']=0;
					if($this->order->save($data)){
						$this->apiReturn(200,'订单取消成功');
					}else{
						$this->apiReturn(404,'订单取消失败');
					}
				}else{
					$this->apiReturn(403,'订单状态错误');
				}
			}else{
				$this->apiReturn(402,'暂无该订单信息');
			}
		}else{
			$this->apiReturn(401,'必须传入订单ID及当前登陆用户ID');
		}
	}
	/**
	 * [sendApi 商家订单发货]
	 * @return [type] [description]
	 */
	public function sendGoodsApi(){
		$data['id']=I('param.id');
		if($data['id']){
			$oneOrder=$this->order->field('id,status')->where($data)->find();
			if($oneOrder){
				if($oneOrder['status'] == 3){
					$data['status']=2;
					$data['sendtime']=time();
					if($this->order->save($data)){
						$this->apiReturn(200,'发货成功');
					}else{
						$this->apiReturn(404,'发货失败');
					}
				}else{
					$this->apiReturn(403,'订单状态错误');
				}
			}else{
				$this->apiReturn(402,'暂无该订单信息');
			}
		}else{
			$this->apiReturn(401,'必须传入订单ID');
		}
	}
	/**
	 * [checkPickApi 确认收货]
	 * @return [type] [description]
	 */
	public function checkPickApi(){
		$data['id']=I('param.id');
		$data['uid']=I('param.uid');
		if(isset($data['id']) && isset($data['uid'])){
			$oneOrder=$this->order->field('id,status')->where($data)->find();
			if($oneOrder){
				if($oneOrder['status'] == 2 || $oneOrder['status'] == 3){
					$data['status']=1;
					if($this->order->save($data)){
						$this->apiReturn(200,'确认收货成功');
					}else{
						$this->apiReturn(404,'确认收货失败');
					}
				}else{
					$this->apiReturn(403,'订单状态错误');
				}
			}else{
				$this->apiReturn(402,'暂无该订单信息');
			}
		}else{
			$this->apiReturn(401,'必须传入订单ID及当前登陆用户ID');
		}
	}
	/**
	 * [detailApi 订单详情]
	 * @return [type] [description]
	 */
	public function detailApi(){
		$data['id']=I('param.id');
		if(isset($data['id'])){
			$oneOrder=$this->order->field('id,order_no,uid,info,totalfee,paymethod,pickid,status,date')->where($data)->find();
			if($oneOrder){
				$onePick=$this->pick->field('name,phone,place')->where(array('id'=>$oneOrder['pickid']))->find();
				$oneOrder['pick']=$onePick;
				$oneOrder['info']=json_decode($oneOrder['info'],true);
				$oneOrder['detail']=array();
				foreach($oneOrder['info'] as $value){
					if($value['type'] == 1){
						$oneGoods=$this->service->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.thumbpic,a.title,b.id as sellerid,b.shopname,b.phone as sellerphone')->where(array('a.id'=>$value['id']))->find();
					}else{
						$oneGoods=$this->goods->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.thumbpic,a.title,b.id as sellerid,b.username,b.phone as sellerphone')->where(array('a.id'=>$value['id']))->find();
					}
					$oneGoods['num']=$value['num'];
					$oneGoods['type']=$value['type'];
					$oneGoods['price']=$value['price'];
					$oneOrder['detail'][]=$oneGoods;
				}
				unset($oneOrder['pickid']);
				unset($oneOrder['info']);
				$this->apiReturn(200,'返回订单详情成功',$oneOrder);
			}else{
				$this->apiReturn(404,'暂无该订单信息');
			}
		}else{
			$this->apiReturn(401,'参数错误,需传入订单ID');
		}
	}
	/**
	 * [crontabApi 每两个小时执行一次，取消过期订单]
	 * @return [type] [description]
	 */
	public function crontabApi(){
		$map['status']=array('eq','4');
		$map['date']=array('lt',time()-7200);
		$orderlist=$this->order->field('id,status,date')->where($map)->select();
		foreach($orderlist as $value){
			$data=$value['id'];
			$data['status']=0;
			$this->order->save($data);
		}
	}
	/**
	 * [crontabApi2 每3天执行一次，设置确认收货过期的订单为已完成]
	 * @return [type] [description]
	 */
	public function crontabApi2(){
		$time=time()-3600*24*3;
		$sql="SELECT `id`,`status`,`date` FROM app_order WHERE `status`=2 AND `sendtime`>0 AND `sendtime` < $time";
		$orderlist=$this->order->query($sql);
		foreach($orderlist as $value){
			$data=$value['id'];
			$data['status']=1;
			$this->order->save($data);
		}
	}
}