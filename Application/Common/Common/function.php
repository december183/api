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
    * @param string $type//产品类型
    * @return string
    */
    function getTradeNo($type){
        $code=range('A','Z');
        return $code[$type-1].date('Ymd').substr(microtime(),2,3).sprintf('%02d',rand(0,99));
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
    function http_curl($url,$type='get',$postData='',$second=30){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        if($type == 'post'){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        }
        //运行curl
        $res=curl_exec($ch);
        //返回结果
        if($res){
            curl_close($ch);
            return $res;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
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
    /**
     * [getAgeRange ]
     * @param  [int] $date [日期时间戳]
     * @return [string]       [年龄范围]
     */
    function getAgeRange($date){  
        $date1=date('Y-m-d',time());
        $data2=date('Y-m-d',$date); 
        list($Y1,$m1,$d1)=explode('-',$date1);  
        list($Y2,$m2,$d2)=explode('-',$date2);  
        $Y=$Y2-$Y1;  
        $m=$m2-$m1;  
        $d=$d2-$d1;  
        if($d<0){  
            $d+=(int)date('t',strtotime("-1 month $date2"));  
            $m--;  
        }  
        if($m<0){  
            $m+=12;  
            $Y--;  
        }
        if($Y < 3){
            $agerange='0-3';
        }elseif(($Y > 3 && $Y < 6) || $Y == 3){
            $agerange='3-6';
        }elseif(($Y > 6 && $Y <12) || $Y == 6){
            $agerange='6-12';
        }else{
            $agerange='';
        }
        return $agerange;
    } 
    /**
     * [getDaysApart 计算相隔日期]
     * @param  [int] $date [日期时间戳]
     * @return [array]       [相隔日期]
     */
    function getDaysApart($date){  
        $date1=date('Y-m-d',time());
        $data2=date('Y-m-d',$date); 
        list($Y1,$m1,$d1)=explode('-',$date1);  
        list($Y2,$m2,$d2)=explode('-',$date2);  
        $Y=$Y2-$Y1;  
        $m=$m2-$m1;  
        $d=$d2-$d1;  
        if($d<0){  
            $d+=(int)date('t',strtotime("-1 month $date2"));  
            $m--;  
        }  
        if($m<0){  
            $m+=12;  
            $Y--;  
        }
        return array('year'=>$Y,'month'=>$m,'day'=>$d);
    }
    /**
     * [toUrlParam 将参数拼接为url:key=value&key=value]
     * @param  [array] $params []
     * @return [string]        []
     */
    function toUrlParam($params){
        $string='';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                $array[] = $key.'='.$value;
            }
            $string = implode("&",$array);
        }
        return $string;
    }
    /**
     * [toUrlFormatParam 将参数拼接为url:key="value"&key="value"]
     * @return [type] [description]
     */
    function toUrlFormatParam($params){
        $string='';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                $array[] = $key.'='.'"'.$value.'"';
            }
            $string = implode("&",$array);
        }
        return $string;
    }
    //数组转XML
    function arrayToXml($params)
    {
        if(!is_array($params)|| count($params) <= 0){
            return false;
        }
        $xml = "<xml>";
        foreach ($params as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                 $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    //将XML转为array
    function xmlToArray($xml)
    {    
        if(!$xml){
            return false;
        }
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }
    /**
     * [decodeUnicode 对unicode再进行解码]
     * @param  [string] $str [description]
     * @return [type]      [description]
     */
    function decodeUnicode($str){
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
        create_function(
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
        ),
        $str);
    }
