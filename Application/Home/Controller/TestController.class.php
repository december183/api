<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends Controller {
	private $service=null;
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->service=D('Service');
		$this->user=D('User');
	}
    public function index(){
        /*$data=array('id'=>1,'username'=>'singwa');
		$message='数据返回成功！';
		//$this->apiReturn(400,$message,$data,'xml');
		//$this->apiNotice(401,'数据输入有误！','xml');
		$this->apiReturn(200,$message,'');*/
		$sql="SHOW TABLE STATUS LIKE 'app_service'";
		$res=$this->service->query($sql);
		var_dump($res);
		// $str={"timeout_express":"30m","seller_id":"","product_code":"QUICK_MSECURITY_PAY","total_amount":"0.01","subject":"1","body":"我是测试数据","out_trade_no":"IQJZSRC1YMQB5HU"};
		// $data['time_express']='30m';
		// $data['seller_id']='2088102147948060';
		// $data['product_code']='QUICK_MSECURITY_PAY';
		// $data['total_amount']='0.01';
		// $data['subject']='1';
		// $data['body']='我是测试数据';
		// $data['out_trade_no']='IQJZSRC1YMQB5HU';
		// echo decodeUnicode(json_encode($data));
    }
    public function import(){
    	vendor('PHPExcel.PHPExcel');
    	$filename=APP_ROOT.'/info.xlsx';
    	$objPHPExcel=\PHPExcel_IOFactory::load($filename);
    	$sheetCount=$objPHPExcel->getSheetCount();
    	for($i=0;$i<$sheetCount;$i++){
    		$arr=$objPHPExcel->getSheet($i)->toArray();
    		foreach($arr as $values){
				$values=array_filter($values);
				if(!empty($values)){
					$keys=array('shopname','shopdescript','location','agerange','classes','level','realname','phone','pass','alipay','weichat','date');
	    			$data=array_combine($keys,$values);
	    			$this->user->add($data);
	    			/*if($insertId=$this->user->add($data)){
	    				
	    			}*/
				}else{
					unset($values);
				}
    		}
    	}
    }
    public function getHxtoken(){
    	$url='https://a1.easemob.com/1197160927115212/mamaapp/token';
    	$data=array(
    		'grant_type'=>'client_credentials',
    		'client_id'=>'YXA6AIlskIRdEeaq_MFIVFiLWA',
    		'client_secrect'=>'YXA6XiIojU12bDAAsqus6xhPrHT4j_8'
    	);
    	$res=http_curl($url,'post',$data);
    	var_dump($res);
    }
}