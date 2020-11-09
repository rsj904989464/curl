<?php
class DomainAnalyse {
    protected $domain = '';
    protected $reg = [
        'domain' => '/^[0-9a-z]([0-9a-z]|(-[0-9a-z]+))*$/' ,
        'num' => '/^[\d]+$/' ,
        'letter' => '/^[a-z]+$/' ,
        'letter_sheng' => '/^[b|p|m|f|d|t|n|l|g|k|h|j|q|x|r|z|c|s|y|w]+$/' ,
    ];

    public function __construct($domain) {
        $this->domain = strtolower(rtrim($domain,'.com'));
        $this->filter();
    }

    function index(){
        return $this->mixed();
    }

    function filter(){
        if(
            !$this->domain ||
            !preg_match($this->reg['domain'],$this->domain) ||
            preg_match_all('/\./',$this->domain) >= 2
        ) throw new Exception('请输入正确的域名');
    }

    function num(){
        return preg_match($this->reg['num'],$this->domain) ? strlen($this->domain).'数字域名':false;
    }

    function letter(){
        return preg_match($this->reg['letter'],$this->domain) ? strlen($this->domain).'字母域名':false;
    }

    function letter_sheng(){
        return preg_match($this->reg['letter_sheng'],$this->domain) ? strlen($this->domain).'声母域名':false;
    }

    /**
     * 杂米
     */
    function mixed(){
        if(($res = $this->num()) || ($res = $this->letter_sheng()) || ($res = $this->letter())){
            return $res;
        }
        return ($length = strlen($this->domain)) <= 3 ? $length.'杂域名':'杂米域名';
    }


}