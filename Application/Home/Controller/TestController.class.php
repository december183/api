<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends Controller {
    public function index(){
        $data=array('id'=>1,'username'=>'singwa');
		$message='数据返回成功！';
		$this->apiReturn(400,$message,$data,'xml');
		//$this->apiNotice(401,'数据输入有误！','xml');
    }
}