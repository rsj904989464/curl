<?php
require_once 'Curl.php';

class Ocr{
    use Curl;
    protected static $grant_type = 'client_credentials';
    protected static $client_id = 'GUH4WbZGsQo3p0LO5KmgBX1E';
    protected static $client_secret = 'i7GRmFdyzzb3dYlTYzY1iWCGoNd7vXtx';
    protected static $token_url = 'https://aip.baidubce.com/oauth/2.0/token';

    public static function get_token(){
        $res = self::post(self::$token_url,"grant_type=".self::$grant_type."&client_id=".self::$client_id."&client_secret=".self::$client_secret);

        if(!$res) throw new Exception('token获取失败');
        return $res['access_token'];
    }
    
    public static function verify($url = '',$params){
        if(!$url) $url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic?access_token=' . self::get_token();
        return self::post($url,$params);
    }
    
}
