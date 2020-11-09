<?php
require_once 'CurlWww.php';
try {
    echo "账户余额：".(new CurlWww())->get_account_amt();
}catch (Exception $ex){
    echo "验证码图片：<img src='code.png' /> <br/>";
    echo "ocr识别验证码：".CurlWww::ocr_code().'<br/>';
    echo "账户余额获取异常：".$ex->getMessage()."<br/>";
    echo "注意：Ocr识别有可能出错，请多刷新几次;CurlWww类中含手动录入验证码代替ocr识别";
}




