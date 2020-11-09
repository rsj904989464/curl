<?php
trait curl {
    public static function post($url,$data,$opt = []){
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);//在尝试连接时等待的秒数。设置为0，则无限等待
        if(isset($opt['referer'])){
            curl_setopt($curl, CURLOPT_REFERER, $opt['referer']);
        }
        if(isset($opt['useragent'])){
            curl_setopt($curl, CURLOPT_USERAGENT, $opt['useragent']);
        }
        if(isset($opt['cookiefile'])){//读取cookie
            curl_setopt($curl, CURLOPT_COOKIEFILE, $opt['cookiefile']);
        }
        if(isset($opt['cookiejar'])){ //存储cookie
            curl_setopt($curl, CURLOPT_COOKIEJAR, $opt['cookiejar']);
        }
        if(isset($opt['header'])){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $opt['header']);
        }
        $res = curl_exec($curl);//运行curl
        curl_close($curl);
        if($str_arr = json_decode($res,1)) {
            return $str_arr;
        }
        return $res;
    }

    public static function get($url,$opt = []){
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        if(isset($opt['cookiefile'])){//读取cookie
            curl_setopt($curl, CURLOPT_COOKIEFILE, $opt['cookiefile']);
        }
        if(isset($opt['cookiejar'])){ //存储cookie
            curl_setopt($curl, CURLOPT_COOKIEJAR, $opt['cookiejar']);
        }
        if(isset($opt['referer'])){
            curl_setopt($curl, CURLOPT_REFERER, $opt['referer']);
        }
        $res = curl_exec($curl);//运行curl
        curl_close($curl);
        return $res;
    }

}