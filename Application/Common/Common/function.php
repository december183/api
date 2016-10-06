<?php
    /**
    *函数：获取客户端IP
    * @param null
    * @return ip
    */
    function getClientIP(){
        $user_IP=$_SERVER['HTTP_VIA'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['HTTP_CLIENT_IP'];
        return($user_IP ? $user_IP : $_SERVER['REMOTE_ADDR']);
    }

    /**
     * 函数：加密
     * @param string            密码
     * @return string           加密后的密码
     */
    function password($password){
        for ($i=0; $i < 3; $i++) { 
            $password = sha1(strrev($password));
        }
        return $password;
    }

    /**
    * 生成订单号
    *
    * @param string $cid//产品类型
    * @return string
    */
    function getTradeNo($cid){
        $code=range('A','Z');
        return $code[$cid-1].date('Ymd').substr(microtime(),2,3).sprintf('%02d',rand(0,99));
    }

    /**
    * 加密函数
    *
    * @param string $str
    * @param string $key
    * @return string
    */
    function passport_encrypt($str,$key){ //加密函数
        srand((double)microtime() * 1000000);
        $encrypt_key=md5(rand(0, 32000));
        $ctr=0;
        $tmp='';
        for($i=0;$i<strlen($str);$i++){
            $ctr=$ctr==strlen($encrypt_key) ? 0 : $ctr;
            $tmp.=$encrypt_key[$ctr].($str[$i] ^ $encrypt_key[$ctr++]);
        }
        return base64_encode(passport_key($tmp,$key));
    }

    /**
    * 解密函数
    *
    * @param string $str
    * @param string $key
    * @return string
    */
    function passport_decrypt($str,$key){ //解密函数
        $str=passport_key(base64_decode($str),$key);
        $tmp='';
        for($i=0;$i<strlen($str);$i++){
            $md5=$str[$i];
            $tmp.=$str[++$i] ^ $md5;
        }
        return $tmp;
    }

    /**
    * 解密辅助函数
    *
    * @param string $str
    * @param string $encrypt_key
    * @return string
    */
    function passport_key($str,$encrypt_key){
        $encrypt_key=md5($encrypt_key);
        $ctr=0;
        $tmp='';
        for($i=0;$i<strlen($str);$i++){
            $ctr=$ctr==strlen($encrypt_key)?0:$ctr;
            $tmp.=$str[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }
    
    /**
    * 获取当前页面url
    * @param void
    * @return string
    */
    function getCurURI(){
        return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
    }

    /**
    * 将二维数组转为一维数组
    * @param array $data 传入的二维数组
    * @param string $key 传入的key
    * @return array $resArr 返回一维数组
    */
    function toOneDimensionalArray($data,$key){
        $resArr=array();
        foreach($data as $value){
            $resArr[]=$value[$key];
        }
        return $resArr;
    }

    /**
     * @param  string $url 获取网页资源或接口url
     * @param  string $type get/post获取
     * @param  string $postData post方式时传递的数据
     * @return [type] $res 返回的数据，一版为string或json格式
     */
    function http_curl($url,$type='get',$postData=''){
       $ch = curl_init();
       curl_setopt($ch,CURLOPT_URL,$url);
       curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
       if($type == 'post'){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
       }
       $res=curl_exec($ch);
       curl_close($ch);
       return $res;
    }
    
    /**
     * [isMobile 判断是否为手机访问]
     * @return boolean [description]
     */
    function isMobile(){
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
            return true;
     
        //此条摘自TPM智能切换模板引擎，适合TPM开发
        if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
            return true;
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
        //判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
            );
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
