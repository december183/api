<?php
namespace Home\Controller;
use Think\Controller;

class ComController extends Controller{
	public function upload(){
        $upload=new \Think\Upload();
        $upload->maxSize=3145728;
        $upload->exts=array('jpg','gif','png','jpeg');
        $upload->rootPath='./Uploads/image/';
        $upload->savePath='';
        $info=$upload->upload();
        if(!$info){
            $this->apiReturn(401,$upload->getError());
        }else{
        	foreach($info as $file){
        		$pathArr[] = APP_ROOT.'/Uploads/image/'.$file['savepath'].$file['savename'];
        	}
            return $pathArr;
        }
    }
    public function upOne(){
        header("Content-Type: text/html; charset=UTF-8");
        $upload=new \Think\Upload();
        $upload->maxSize=3145728;
        $upload->exts=array('jpg','gif','png','jpeg');
        $upload->rootPath='./Uploads/image/';
        $upload->savePath='';
        $info=$upload->uploadOne($_FILES['file']);
        if(!$info){
            $this->apiReturn(401,$upload->getError());
        }else{
            $path=APP_ROOT.'/Uploads/image/'.$info['savepath'].$info['savename'];
            $imgArr=getimagesize($path);
            if($imgArr[0] < 800 && $imgArr[1] < 800){
                $path=str_replace('\\', '/',$path);
                $data['mainpic']=strstr($path,__ROOT__.'/Uploads/image/');
            }else{
                $data['mainpic']=$this->thumb($path);
            }
            $data['thumbpic']=$this->thumb($path,100,100);
            $this->apiReturn(200,'图片上传成功',$data);
        }
    }
    /**
     * [upSourceOne 上传原始大小单图商铺banner]
     * @return [type] [description]
     */
    public function upSourceOne(){
        $upload=new \Think\Upload();
        $upload->maxSize=3145728;
        $upload->exts=array('jpg','gif','png','jpeg');
        $upload->rootPath='./Uploads/image/';
        $upload->savePath='';
        $info=$upload->uploadOne($_FILES['file']);
        if(!$info){
            $this->apiReturn(401,$upload->getError());
        }else{
            $data['shopbanner']=__ROOT__.'/Uploads/image/'.$info['savepath'].$info['savename'];
            $this->apiReturn(200,'图片上传成功',$data);
        }
    }
    /**
     * [upAvatar 上传用户头像]
     * @return [type] [description]
     */
    public function upAvatar(){
        $upload=new \Think\Upload();
        $upload->maxSize=3145728;
        $upload->exts=array('jpg','gif','png','jpeg');
        $upload->rootPath='./Uploads/image/';
        $upload->savePath='';
        $info=$upload->uploadOne($_FILES['file']);
        if(!$info){
            $this->apiReturn(401,$upload->getError());
        }else{
            $path=APP_ROOT.'/Uploads/image/'.$info['savepath'].$info['savename'];
            return $this->thumb($path,100,100);
        }
    }
    /**
     * [upBanner IOS上传商铺banner]
     * @return [type] [description]
     */
    public function upBanner(){
        $upload=new \Think\Upload();
        $upload->maxSize=3145728;
        $upload->exts=array('jpg','gif','png','jpeg');
        $upload->rootPath='./Uploads/image/';
        $upload->savePath='';
        $info=$upload->upload();
        if(!$info){
            $this->apiReturn(401,$upload->getError());
        }else{
            foreach($info as $file){
                $data['shopbanner'].= __ROOT__.'/Uploads/image/'.$file['savepath'].$file['savename'].';';
            }
            $data['shopbanner']=substr($data['shopbanner'], 0, -1);
            $this->apiReturn(200,'上传成功',$data);
        }
    }
    public function thumb($path,$width=800,$height=800){
        $image=new \Think\Image();
    	$image->open($path);
        $_start=substr($path,0,-strlen(strrchr($path,'.')));
        $_end=strrchr($path,'.');
        $thumb_path=$_start.$width.'x'.$height.'_thumb'.$_end;
        $image->thumb($width,$height)->save($thumb_path);
        $thumb_path=str_replace('\\', '/', $thumb_path);
        return strstr($thumb_path,__ROOT__.'/Uploads/image/');
    }
    public function uploadMainPic(){
        // $this->apiReturn('101','调试',$_FILES);
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
            $this->apiReturn(200,'图片上传成功',$data);
        }else{
            $this->apiReturn(402,'请上传商品主图');
        }
    }
    public function uploadMainPic2(){
        // $this->apiReturn(101,'调试',$_FILES);
        $number=I('param.number');
        $upload=new \Think\Upload();
        $upload->maxSize=3145728;
        $upload->exts=array('jpg','gif','png','jpeg');
        $upload->rootPath='./Uploads/image/';
        $upload->savePath='';
        for($i=0;$i<$number;$i++){
            if($_FILES['file'.$i]['tmp_name']){
                $info=$upload->uploadOne($_FILES['file'.$i]);
                if(!$info){
                    $this->apiReturn(401,$upload->getError());
                }else{
                    $path=APP_ROOT.'/Uploads/image/'.$info['savepath'].$info['savename'];
                    $imgArr=getimagesize($path);
                    if($imgArr[0] < 800 && $imgArr[1] < 800){
                        $path=str_replace('\\', '/',$path);
                        $data['mainpic'].=strstr($path,__ROOT__.'/Uploads/image/').';';
                    }else{
                        $data['mainpic'].=$this->thumb($path).';';
                    }
                    if($i == 1){
                        $data['thumbpic']=$this->thumb($path,100,100);
                    }
                }
            }
        }
        $data['mainpic']=substr($data['mainpic'],0,-1);
        $this->apiReturn(200,'图片上传成功',$data);
    }
    protected function uploadPic(){
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
        return $data;
    }
    function wxPayApi($param=array()){
        $data['appid']='wx0f25933afc3cc9a4';
        $data['mch_id']='1398651802';
        $data['nonce_str']=md5(time().mt_rand(0,1000));
        $data['out_trade_no']=$param['order_no'];
        $data['body']='妈妈应用APP'.$param['order_no'].'订单微信支付';
        $data['total_fee']=$param['totalfee']*100;
        $data['spbill_create_ip']=$param['client_ip'];
        $data['notify_url']='http://www.86qu.com/api/Home/Order/wxNotifyUrl';
        $data['trade_type']='APP';
        $key='e48dc60a89ed2fc6da70f8eae5cac7ef';
        ksort($data);
        $sign=strtoupper(md5(toUrlParam($data).'&key='.$key));
        $data['sign']=$sign;
        $url='https://api.mch.weixin.qq.com/pay/unifiedorder';
        $content=arrayToXml($data);
        $res=http_curl($url,'post',$content);
        $response=xmlToArray($res);
        $response['package']='Sign=WXPay';
        $response['timestamp']=time();
        return $response;
    }
    public function aliPayApi2($param=array()){
        $private_key='-----BEGIN RSA PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAN5JbFIA2nCZ5Y3n
L7h9CAIo1l6wSDGXFf1L0y319LxR0eI5rSR0WfYw71btUiarlM6Pgv6I2qHkdr3p
b/fMCf4MtTi7FkXaH/tLVsyIZN+E+A7YyhO+1TaQVSOalfDfn841Fybz470eAJ1H
1TmI3XTzU5lTHC0w7LY7HGyJC84lAgMBAAECgYEAvryL+PWIerRjeFcW6JxIweme
wJNM71hwYu+sXrS88tbWXOMWwcAg7ZJh3No48ruqLXCRe62cxOvQQ/dJv3xSWRXT
UALiwxWaXSu7Q/WQd0SOX2kzEio6SYnSHxpeJWxIr0tPPh5h4P+F2dt2vPk3aSzT
Gs8rEETkJW1DkyTFKiUCQQDwFZLF812WUidauRx3fH1P8axXCs6Tl8pGWdZ2XqwX
bXo00EOEXWtdrpD/G9GTArnrM+ZYHdvDQnO7B9WCck/3AkEA7QXP8oK9CMkfL1J/
l/pUZAZYDn+QNBnrpPPKVAOgyFUqGNIS5TT0MvsIehZCBFaR/KVpZTohC+W/g6+U
wv8DwwJAWKROdaG+KxMYDqoL9Z3UEqEzNUv7K4k+mKzwvvGDbn7wQPGoDAYF7yfV
xyr5POMjy9B0upIDots1KZfh3/DezwJAWrzL49obBf8AujwJ7qN9pSEsmrhI+zkl
FevifBE7fxXjXMcnRqnkBpRFpX9Z3JFLp/2nAIKlXgmhLIeOGqxHwwJBAKCH6uTR
0ajH/ximPkEfs+e4xS6Alj5+e/L5sz4d208EOzpCx1ucnLcOhG0bKmWOQ1q5+DxI
O0oX2s5SEzM4mJA=
-----END RSA PRIVATE KEY-----';
        $pi_key=openssl_pkey_get_private($private_key);
        $data['app_id']='2016091201894810';
        $data['method']='alipay.trade.app.pay';
        $data['charset']='utf-8';
        $data['sign_type']='RSA';
        $data['timestamp']=date('Y-m-d H:i:s',time());
        $data['version']='1.0';
        $data['notify_url']='http://www.86qu.com/api/Home/Order/aliNotifyUrl';

        $content['out_trade_no']=$param['order_no'];
        $content['subject']='妈妈应用APP'.$param['order_no'].'订单支付宝支付';
        $content['body']=$param['body'];
        $content['total_amount']=$param['totalfee'];

        // $content['out_trade_no']='2016102672197';
        // $content['subject']='测试';
        // $content['body']='测试';
        // $content['total_amount']=1;
        
        $content['timeout_express']='30m';
        $content['sell_id']='2088402820303709';
        $content['product_code']='QUICK_MSECURITY_PAY';
        $data['biz_content']=decodeUnicode(json_encode($content));
        ksort($data);
        $tempSign=toUrlParam($data);
        openssl_sign($tempSign, $encrypted, $pi_key);
        $sign=base64_encode($encrypted);
        $data['sign']=$sign;
        $data2=array();
        foreach($data as $key=>$value){
            $value=rawurlencode($value);
            $data2[$key]=$value;
        }
        $encodeStr=toUrlParam($data2);
        $signStr=$encodeStr.'&sign='.$sign;
        return array($signStr);
    }
    public function aliPayApi($param=array()){
        $data['app_id']='2016091201894810';
        $data['method']='alipay.trade.app.pay';
        $data['charset']='utf-8';
        $data['sign_type']='RSA';
        $data['timestamp']=date('Y-m-d H:i:s',time());
        $data['version']='1.0';
        $data['notify_url']='http://www.86qu.com/api/Home/Order/aliNotifyUrl';

        $data['out_trade_no']=$param['order_no'];
        $data['paymethod']=$param['paymethod'];
        $data['subject']='妈妈应用APP'.$param['order_no'].'订单支付宝支付';
        $data['body']=$param['body'];
        $data['total_amount']=$param['totalfee'];
        
        $data['timeout_express']='30m';
        $data['sell_id']='2088402820303709';
        $data['product_code']='QUICK_MSECURITY_PAY';
        return $data;
    }
}
