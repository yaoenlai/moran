<?php
// +----------------------------------------------------------------------
// | GamePlat [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.yongshihuyu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Pay\ThinkPay\Pay\Driver;
/**
 * 支付宝驱动
 */
class Nowpay extends \Addons\Pay\ThinkPay\Pay\Pay {
    static $trade_time_out="3600";
	static $pay_channel = array(
		'union' => 11,//11银联-手机/手机网页/；
		'alipay' => 12,//12支付宝-手机/手机网页/电脑
		'weixin' => 13,//13微信支付-手机/手机网页/公众号/电脑
		'weixinH5' => 1301,//1301微信支付-手机网页
		'weixinApp' => 1310,//13微信支付－手机
		//'dcard' => 16,//16点卡支付
		'bank' => 18,//18网银-电脑；
		'boc' => 1801,//中国
		'abc' => 1802,//农业
		'icbc' => 1803,//工商
		'ccb' => 1804,//建设
		'bocom' => 1805,//交通
		'cmb' => 1806,//招商
		'ceb' => 1807,//光大
		'citic' => 1808,//中信
		'spdb' => 1809,//浦发
		'cgb' => 1810,//广发
		'cmbc' => 1811,//民生
		'cib' => 1812,//兴业
		'pingan' => 1813,//平安
		'hxb' => 1814,//华夏
		'jsb' => 1815,//江苏
		'czb' => 1816,//浙商
		'cbhb' => 1817,//渤海
		'hsb' => 1818,//徽商
		'psbc' => 1819,//邮政
		//'scard' => 19,//19充值卡支付
		'unionQuick' => 20,//20银联快捷-电脑；
		'QQ' => 25,//19QQ钱包支付-手机/手机网页/电脑
		//'baidu' => 50,//50百度钱包
		'applePay' => 61,//ApplePay
	);
    
	const TRADE_URL="https://pay.ipaynow.cn";
    const QUERY_URL="https://pay.ipaynow.cn";
    const TRADE_FUNCODE="WP001";
    const QUERY_FUNCODE="MQ001";
    const NOTIFY_FUNCODE="N001";
    const FRONT_NOTIFY_FUNCODE="N002";
    const TRADE_TYPE="01";
    const TRADE_CURRENCYTYPE="156";
    const TRADE_CHARSET="UTF-8";
    //const TRADE_DEVICE_TYPE="06";//06 wap 02 pc
    const TRADE_SIGN_TYPE="MD5";
    const TRADE_QSTRING_EQUAL="=";
    const TRADE_QSTRING_SPLIT="&";
        const TRADE_FUNCODE_KEY="funcode";
        const TRADE_DEVICETYPE_KEY="deviceType";
    const TRADE_SIGNTYPE_KEY="mhtSignType";
    const TRADE_SIGNATURE_KEY="mhtSignature";
	const MHT_SIGN_TYPE_KEY="mhtSignType";
    const MHT_SIGNATURE_KEY="mhtSignature";
	
    const SIGNATURE_KEY="signature";
    const SIGNTYPE_KEY="signType";
    const VERIFY_HTTPS_CERT=false;
	
    protected $config = array(
        'appid' => '',
        'key' => '',
        'wap_appid' => '',
        'wap_key' => '',
        'app_appid' => '',
        'app_key' => '',
    );
	static $appid;
	static $key;
	static $wap_appid;
	static $wap_key;
	static $app_appid;
	static $app_key;

    public function check() {
        if (!$this->config['appid'] || !$this->config['key'] || !$this->config['wap_appid']
			||!$this->config['wap_key'] || !$this->config['app_appid'] || !$this->config['app_key']) {
            E("现在支付设置有误！");
        // }else{
        	// self::$appid = $this->config['appid'];
			// self::$key = $this->config['key'];
			// self::$app_appid = $this->config['app_appid'];
			// self::$app_key = $this->config['app_key'];
			// self::$wap_appid = $this->config['wap_appid'];
			// self::$wap_key = $this->config['wap_key'];
        }
        return true;
    }

    public function buildRequestForm($pay_data) {
        $req["mhtCharset"]=self::TRADE_CHARSET;//UTF-8
        $req["mhtCurrencyType"]=self::TRADE_CURRENCYTYPE;//156(人民币)
        $req["mhtOrderAmt"]=$pay_data['money']*100;//订单金额
        $req["mhtOrderDetail"]=$pay_data['body'];//描述
    	$req["mhtOrderName"]=$pay_data['title'];//名称
        $req["mhtOrderNo"]=$pay_data['out_trade_no'];//订单号
        $req["mhtOrderStartTime"]=date("YmdHis");//订单开始时间
        $req["mhtOrderTimeOut"]=self::$trade_time_out;//订单超时时间
        $req["mhtOrderType"]=self::TRADE_TYPE;//1普通消费
    	$req["notifyUrl"]=$this->config['notify_url'];
    	$req["frontNotifyUrl"]=$this->config['return_url'];
		if(!empty($pay_data['channel_type']))
			$req["payChannelType"]=self::$pay_channel[$pay_data['channel_type']];
		
		/*
		 * appId=1461307490695331&
		 * mhtCharset=UTF-8&
		 * mhtCurrencyType=156&
		 * mhtOrderAmt=1&
		 * mhtOrderDetail=金币&
		 * mhtOrderName=魔神&
		 * mhtOrderNo=14701341785171855&
		 * mhtOrderStartTime=20160802183618&
		 * mhtOrderTimeOut=3600&
		 * mhtOrderType=01&
		 * mhtReserved=描述&
		 * notifyUrl=http://open.t.yongshihuyu.com/sdk/nowpay/api/notify.php&
		 * payChannelType=13
         */
		// 是否调用APP移动支付
        if (C('IS_API')) {
        	if($pay_data['channel_type'] == 'weixinH5'){
				self::$appid = $this->config['wap_appid'];
				self::$key = $this->config['wap_key'];
				$req["deviceType"]="06";
				$req["funcode"]=self::TRADE_FUNCODE;
			}else{
				self::$appid = $this->config['app_appid'];
				self::$key = $this->config['app_key'];
			}
			
        	$req["appId"]=self::$appid;//应用ID
        	//$req["funcode"]=self::QUERY_FUNCODE;
        	$req["mhtReserved"]=$req["mhtOrderAmt"];//自定义参数
            
        	$req["mhtSignType"]=self::TRADE_SIGN_TYPE;
        	$req["mhtSignature"]=$this->buildSign($req);
			
			
            $return['json'] = json_encode($req);
            $return['string'] = self::createLinkString($req,FALSE,FALSE);
			if($pay_data['channel_type'] == 'weixinH5')//如果是微信H5 那么返回Tn
				$return['tn'] = sendHttp(self::TRADE_URL, $return['string'],'post');
			//$return['sHtml'] = $this->_buildForm($return['tn'], 'weixin://wap/pay', 'get');
            return $return;
        } else {
        	$req["funcode"]=self::TRADE_FUNCODE;
        	$req["frontNotifyUrl"]=$this->config['return_url'];
			if(\Common\Util\Device::isWap()){
				self::$appid = $this->config['wap_appid'];
				self::$key = $this->config['wap_key'];
				//$req["deviceType"]=self::TRADE_DEVICE_TYPE;
        		$req["appId"]=self::$appid;//应用ID
				$req["deviceType"]="06";
        		$req["mhtReserved"]=$req["deviceType"];//自定义参数
    			//dump($reqstr);
    			//$req["payChannelType"]="";
	        	$req["mhtSignType"]=self::TRADE_SIGN_TYPE;
	        	$req["mhtSignature"]=$this->buildSign($req);
				
	    		$reqstr = self::createLinkString($req,FALSE,FALSE);
				if($pay_data['channel_type'] == 'weixinH5'){//dump($reqstr);
	    			$response = sendHttp(self::TRADE_URL, $reqstr,'post');//file_get_contents(self::TRADE_URL.'?'.$req);
	    			$response = self::convertUrlQuery($response);
					$param = parse_url(urldecode($response['tn']));
	    			$tnArray = self::convertUrlQuery(urldecode($param['query']));
					$sHtml = $this->_buildForm($tnArray, 'weixin://wap/pay?'.$tn, 'get');//dump($sHtml);exit;
					return $sHtml;exit;
	    		}
        		
			}else{
	        	self::$appid = $this->config['appid'];//pc支付时用的参数
				self::$key = $this->config['key'];
        		$req["appId"]=self::$appid;//应用ID
				$req["deviceType"]="02";
        		$req["mhtReserved"]=$req["deviceType"];//自定义参数
        		//TODO 功能没完成
				if($pay_data['channel_type'] == 'weixinH5'){
					$req["deviceType"]="08";
	    			$req["payChannelType"] = self::$pay_channel['weixin'];
	    			$req["outputType"] = '0';
		        	$req["mhtSignType"]=self::TRADE_SIGN_TYPE;
		        	$req["mhtSignature"]=$this->buildSign($req);//dump($req);
					$reqstr = self::createLinkString($req,FALSE,FALSE);
					$response = sendHttp(self::TRADE_URL, $reqstr,'post');//file_get_contents(self::TRADE_URL.'?'.$req);
	    			$response = self::convertUrlQuery($response);
	    			$response['responseMsg'] = urldecode($response['responseMsg']);
	    			//dump($response);
					$param = parse_url(urldecode($response['tn']));//dump($param);
					$sHtml = '<img style="" src="'.urldecode($response['tn']).'">';//echo ($sHtml);exit;
					//$sHtml = $this->_buildForm($tnArray, 'weixin://wap/pay?'.$tn, 'get');//dump($sHtml);exit;
					return $sHtml;
	    		}
			}
			//$req["payChannelType"]="";
        	$req["mhtSignType"]=self::TRADE_SIGN_TYPE;
        	$req["mhtSignature"]=$this->buildSign($req);
			
			//dump($req);exit;
            //$req_str=self::buildReq($req);
            //header("Location:".self::TRADE_URL."?".$req_str);
			$sHtml = $this->_buildForm($req, self::TRADE_URL, 'get');
            return $sHtml;
        }
    }

	/**
     * 异步通知验证成功返回信息
     */
    public function notifySuccess() {
        return "success=Y";
    }


    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    public function verifyNotify($notify) {
        //生成签名结果
        $isSign = $this->verifySignature($notify);
        //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
        $response = array('transStatus'=>'A001');
        if (!empty($notify["mhtOrderNo"])) {
            $response = $this->getResponse($notify);
        }

        if ($response['transStatus'] == 'A001' && $isSign) {
            $this->setInfo($notify);
            return true;
        } else {
            return false;
        }
    }

    protected function setInfo($notify) {
        $info = array();
        //支付状态
        $info['status'] = ($notify['tradeStatus']!=""&&$notify['tradeStatus']=="A001") ? true : false;
        $info['money'] = $notify['mhtOrderAmt'];
        $info['out_trade_no'] = $notify['mhtOrderNo'];
        $this->info = $info;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify(实际只要appId和out_trade_no) 商户欲查询交易订单号
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    public function getResponse($notify) {
        $req=array();
		$req["deviceType"]=$req["mhtReserved"];//自定义参数
        $req["funcode"]=self::QUERY_FUNCODE;
        $req["appId"]=$notify['appId'];
        $req["mhtOrderNo"]=$notify['mhtOrderNo'];//商户欲查询交易订单号
        $req["mhtCharset"]=self::TRADE_CHARSET;
        $req["mhtSignature"]=$this->buildSignature($req);
        $req["mhtSignType"]=self::TRADE_SIGN_TYPE;
     	$resp=array();
        $this->query($req, $resp);
        //print_r($resp);
        return $resp;
    }
	
	
	/**
     *   将query数据变成数组
     * @return 排重后的数组
     */
	public static function convertUrlQuery($query){  
	    $queryParts = explode('&', $query);  
	    $params = array();  
	    foreach ($queryParts as $param){  
	        $item = explode('=', $param);  
	        $params[$item[0]] = $item[1];  
	    }  
	    return $params;  
	}
	
	
	/**
     *   参数排重
     * @return 排重后的数组
     */
	public static function paraFilter(Array $params) {
        $result=array();
        $flag=$params[self::TRADE_FUNCODE_KEY];
        foreach($params as $key => $value){
            if (($flag==self::TRADE_FUNCODE)&&!($key==self::TRADE_FUNCODE_KEY||$key==self::TRADE_DEVICETYPE_KEY
                ||$key==self::TRADE_SIGNTYPE_KEY||$key==self::TRADE_SIGNATURE_KEY)){
                $result[$key]=$value;
                continue;
            }
            if(($flag==self::NOTIFY_FUNCODE||$flag==self::FRONT_NOTIFY_FUNCODE)&&!($key==self::SIGNTYPE_KEY||$key==self::SIGNATURE_KEY)){
                $result[$key]=$value;
                continue;
            }
            if (($flag==self::QUERY_FUNCODE)&&!($key==self::TRADE_SIGNTYPE_KEY||$key==self::TRADE_SIGNATURE_KEY
                ||$key==self::SIGNTYPE_KEY||$key==self::SIGNATURE_KEY)) {
                $result[$key]=$value;
                continue;
            }
			if(empty($flag)&&!($key==self::SIGNATURE_KEY||$key==self::SIGNTYPE_KEY
                    ||$key==self::MHT_SIGN_TYPE_KEY||$key==self::MHT_SIGNATURE_KEY)){
                $result[$key]=$value;
            }
        }
        return $result;
    }
    
	/**
     *   创建sign
     * @return sign
     */
    public function buildSignature(Array $para){
    	if(!empty($para['deviceType'])){
    		switch ($para['deviceType']) {
	            case '02'://pc
	                $key = $this->config['key'];
	                break;
	            case '06'://wap
	                $key = $this->config['wap_key'];
	                break;
	            case '08'://主扫
	                $key = $this->config['key'];
	                break;
	            default://App
	                $key = $this->config['app_key'];
	                break;
	        }
    	}else{
	        $key = $this->config['app_key'];
        }
		$para=self::paraFilter($para);
    	
        $prestr=self::createLinkString($para, true, false);
        $prestr.=self::TRADE_QSTRING_SPLIT.md5($key);
 
        return md5($prestr);
    }
	
	/**
     *   参数拼接
     * @return 拼接后的字符串
     */
    public static function createLinkString(Array $para,$sort,$encode) {
        if ($sort) {
            $para=self::argSort($para);
        }
        $linkStr = '';
        foreach ($para as $key => $value){
            if ($encode) {
                $value=urlencode($value);
            }
            $linkStr.=$key.self::TRADE_QSTRING_EQUAL.$value.self::TRADE_QSTRING_SPLIT;
        }
        $linkStr=substr($linkStr, 0,count($linkStr)-2);
        return $linkStr;
    }
	/**
     *   参数自然排序
     * @return 排序后的数组
     */
    private static function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }
	
	
	
	//Service
	/**
     *   查询订单
     * @return 订单详情
     */
	public function query(Array $params,Array &$resp) {
        $req_str=self::buildReq($params);
        $resp_str=self::sendMessage($req_str, self::QUERY_URL);
        return $this->verifyResponse($resp_str, $resp);
    }
    /**
     * 创建Sign
     * @return 返回Sign
     */
    public function buildSign(Array $params) {
        //$filteredReq=self::paraFilter($params);
        //return $this->buildSignature($filteredReq);
		return $this->buildSignature($params);
    }
    /**
     * 将数组参数生成字串
     * @return 返回字串
     */
    private static function buildReq(Array $params) {
        return self::createLinkString($params, false, true);
    }
    /**
     * 验证签名
	 * @param $para_temp 通知返回来的参数数组
     * @return 返回验证结果
     */
    public function verifySignature($para){
        $respSignature=$para[self::SIGNATURE_KEY];
        //$filteredReq=self::paraFilter($para);
        $signature=$this->buildSignature($para);
        if ($respSignature!=""&&$respSignature==$signature) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    /**
     * 验证
     * @return 返回Sign
     */
    public function verifyResponse($resp_str,&$resp){
        if ($resp_str!="") {
            parse_str($resp_str,$para);
    
            $signIsValid=$this->verifySignature($para);
            $resp=$para;
            if ($signIsValid) {
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }
	
	/**
     * 发送信息
     * 
     * @param type $req_content 请求字符串
     * @param type $url 请求地址
     * @return type 应答消息
     */
    static function sendMessage($req_content,$url) {
        if(function_exists("curl_init")){
            $curl=  curl_init();
            $option=array(
                CURLOPT_POST=>1,
                CURLOPT_POSTFIELDS=>$req_content,
                CURLOPT_URL=>$url,
                CURLOPT_RETURNTRANSFER=>1,
                CURLOPT_HEADER=>0,
                CURLOPT_SSL_VERIFYPEER=>  self::VERIFY_HTTPS_CERT,
                CURLOPT_SSL_VERIFYHOST=>  self::VERIFY_HTTPS_CERT
            );
            curl_setopt_array($curl, $option);
            $resp_data=  curl_exec($curl);
            if($resp_data==FALSE){
                curl_close($curl);
            }else{
                curl_close($curl);
                return $resp_data;
            }
        }
    }
}
