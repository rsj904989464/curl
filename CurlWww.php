<?php
require_once 'Curl.php';
require_once 'Ocr.php';

class CurlWww {
      use Curl;

      protected  $url = "https://www.chengmi.cn";
      protected  $code_url = "https://www.chengmi.cn/member/code.aspx";//获取图片验证码url
      protected  $login_url = 'https://www.chengmi.cn/member/ajax/User.ashx';
      protected  $cookie_file = '';
      protected $token = '';
      protected $code_img = './code.png'; //验证码

      public function __construct() {
          $this->code_url .= '?'.preg_replace('/ /','',microtime());
          $this->cookie_file = dirname(__FILE__) . '/cookie.txt';
      }

    /**
     *  获取账户余额
     */
      public function get_account_amt(){
          $this->login();
          $contents = Curl::get('https://www.chengmi.cn/userpanel',[
              'cookiefile' => $this->cookie_file
          ]);
          preg_match_all('/<td height="36" align="center" class="hsac" style="font-size: 18px;">(.*?)<\/td>/',preg_replace("/[\t\n\r]+/","",$contents),$match);
          return trim($match[1][0]);
      }

    /**
     *  注意：OCR识别有可能会失败,暂未做处理
     * @param bool $ocr  true,ocr识别验证码   false,将code.png验证码 手动填入 code.txt
     * @return false|mixed|string
     */
    public static function ocr_code($ocr = true){
        if($ocr){
            $res = Ocr::verify('',[
                'image' => base64_encode(file_get_contents('code.png'))
            ]);
            $reg = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\（|\）|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\||\s+/";
            return preg_replace($reg,'',$res['words_result'][0]['words']);
        }

        sleep(10);

        return file_get_contents('code.txt');
    }

      protected function login(){
          $this->get_token_cookie();
          $this->get_code_img();
          $code = self::ocr_code();

          $data = "username=Rsj123456%40mail.top&code={$code}&userpwd=00e4485618f6f635f9f3e7eb03a1af06&token={$this->token}&b_type=1&lang=f643373e6fdb32d38bb39e7d66821630&vifrom=05045915769070095147115810855359";
          $res = Curl::post($this->login_url,$data,[
              'cookiefile' => $this->cookie_file ,
              'referer' => $this->url ,
              'useragent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:81.0) Gecko/20100101 Firefox/81.0' ,
              'header' => [
                  'Accept:text/plain, */*; q=0.01',
                  'Accept-Encoding:gzip, deflate, br',
                  'Accept-Language:zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
                  'Connection:keep-alive' ,
                  'Content-Length:'.strlen($data) ,
                  'Content-Type:application/x-www-form-urlencoded',
                  'Host:www.chengmi.cn',
                  'Origin:https://www.chengmi.cn',
                  'X-Requested-With:XMLHttpRequest'
              ],
          ]);
          if(strlen($res) < 300){
              throw new Exception($res);
          }
      }



    protected function get_token_cookie(){
          $html_index = Curl::get($this->url,[
              'cookiejar' => $this->cookie_file
          ]);
          preg_match('<input type="hidden" id="login_token" value="(.*?)" />',$html_index,$match);
          $this->token = $match[1];
      }

      protected function get_code_img(){
          //验证码
          $img = Curl::get($this->code_url,[
              'cookiefile' => $this->cookie_file ,
              'referer' => $this->url
          ]);
          $fp = fopen($this->code_img,"w");//将获取的验证码写入图片中
          fwrite($fp,$img);
      }

}