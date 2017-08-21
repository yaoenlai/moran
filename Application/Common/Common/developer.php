<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

//开发者二次开发公共函数统一写入此文件，不要修改function.php以便于系统升级。

//压缩并加密
function compression($str) {
	$arr = fixedArr();
	$str = base64_encode($str);
	$str = encode($str,$arr);
	return gzencode($str,9);
}

//解压并解密
function decompression($str) {
	$arr = fixedArr();
	$tmp = gzinflate(substr($str,10,-8));
	$tmp = decode($tmp,$arr);
	return base64_decode($tmp);
}

//加密
function encrypt($str) {
	$arr = fixedArr();
	$str = base64_encode($str);
	return encode($str,$arr);
}

//解密
function decrypt($str) {
	$arr = fixedArr();
	$tmp = decode($str,$arr);
	return base64_decode($tmp);
}

/**
 *
 * 加密函数
 *
 * $str 加密的字符串
 * $arr 固定数组
 */
function encode($str,$arr) {
	if ($str == null) {
      return "";
    }

	$rsstr = "x";
	$toarr = str_split($str);
	$arrlenght = count($arr);
	for ($i=0;$i<count($toarr);$i++) {
		$string = ord($toarr[$i]) + ord($arr[$i % $arrlenght]);
		$rsstr .= $string."_";
	}

	$rsstr = substr($rsstr,0,-1);
	$rsstr .= "y";
	return $rsstr;
}

/**
 *
 * 解密函数
 *
 * $str 解密的字符串
 * $arr 固定数组
 */
function decode($str,$arr) {
	if ($str == '') {
      return '';
    }

	$first = substr($str,0,1);
	$end = substr($str,-1);

	if ($first == 'x' && $end == 'y') {
		$str = substr($str,1,-1);
		$toarr = explode("_",$str);
		$arrlenght = count($arr);
		$rsstr = '';
		for ($i=0;$i<count($toarr);$i++) {
			$string = $toarr[$i] - ord($arr[$i % $arrlenght]);
			$rsstr .= chr($string);
		}

		return $rsstr;
	} else {
		return "";
	}
}

//加密的固定数组
function fixedArr() {
	$arr = array( '0', '1', '2', '3', '4', '5', '6', '7', '8',
				'9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
				'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y',
				'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
				'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y',
				'Z', '*', '!' ,'/', '+', '=','#'
	);

	return $arr;
}

/**
 * 生成密文数据
 *
 * @author zxq 2016.06.21
 * $code 状态码，1表示成功
 * $msg 返回提示信息
 * $data 数据，可以是数组
 * $compression 1.密文 2.压缩密文
 */
function returnData($code,$msg,$data=null,$compression = 2) {
	$arr = 	array(
		'code' => $code,
		'msg' => $msg,
		'data' => $data,
	);
	$tmp = json_encode($arr);
	if($compression == 2)
		return compression($tmp);
	return encrypt($tmp);
}

/**
 * 生成明文数据
 *
 * @author zxq 2016.06.22
 * $code 状态码，1表示成功
 * $msg 返回提示信息
 * $data 数据，可以是数组
 */
function returnMsg($code,$msg,$data=null) {
	return array(
		'code' => $code,
		'msg' => $msg,
		'data' => $data,
	);
}

/**
 * 根据infoType生成数据
 *
 * @author zxq 2016.06.22
 * $code 状态码，1表示成功
 * $msg 返回提示信息
 * $data 数据，可以是数组
 * $infoType 0.明文 1.密文 2.压缩密文 3.json明文
 */
function returnInfo($code,$msg,$data=null,$infoType = 0) {
	switch ($infoType) {
		case '0'://明文
			$info['data'] = returnMsg($code,$msg,$data);
			break;
		case '1'://密文
			$info['data'] = returnData($code,$msg,$data,1);
			break;
		case '2'://压缩密文
			$info['data'] = returnData($code,$msg,$data,2);
			break;
		default://当requestData方法扩展更多明文接收数据的时候默认返回数据方式
			$info['data'] = returnMsg($code,$msg,$data);
			break;
	}
	return $info['data'];
}

/**
 * 接口共用解析接收数据函数
 *
 * @author zxq 2016.06.27
 * $code 状态码，1表示成功
 * $msg 返回提示信息
 * $data 数据，可以是数组
 * $compression 0 明文 1.密文 2.压缩密文 3.json明文
 */
function requestData($data,$compression = 0) {
	switch ($compression) {
		case '0'://明文
			$data = I('request.');
			break;
		case '1'://密文
			$data = get_object_vars(json_decode(decrypt($data)));
			break;
		case '2'://压缩密文
			$data = get_object_vars(json_decode(decompression($data)));
			break;
		case '3'://原始post传输json明文
			$data = get_object_vars(json_decode($data));//dump($data);
			break;
	}
	return $data ? $data : false;
}

/**
 * 模拟Post\Get请求
 *
 * @author zxq 2016.06.17
 * @param string 	$Url 	执行请求的Url
 * @param mixed	$Params 表单参数
 * @param string	$Method 请求方法 post / get
 * @param int	$TimeOut 等待时间 s
 * @return string 结果字串
 */

function sendHttp($Url, $Params, $Method='post', $TimeOut=10){
	$rs = 0;
	$Curl = curl_init();//初始化curl
	if ('get' == $Method){//以GET方式发送请求
		curl_setopt($Curl, CURLOPT_URL, "$Url?$Params");
	}else{//以POST方式发送请求
		curl_setopt($Curl, CURLOPT_URL, $Url);
		curl_setopt($Curl, CURLOPT_POST, 1);//post提交方式
		curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params);//设置传送的参数
	}
	curl_setopt($Curl, CURLOPT_HEADER, false);//设置header
	curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
	curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, $TimeOut);//设置等待时间
	$parse_url = parse_url($Url);
	curl_setopt($curl, CURLOPT_COOKIEJAR, RUNTIME_PATH.'Cookie/'.$parse_url['host']); //设置Cookie信息保存在指定的文件中 
	//$Res = strtolower(curl_exec($Curl));//运行curl
	$Res = curl_exec($Curl);//运行curl
	curl_close($Curl);//关闭curl
	//echo $Res;
	return $Res;
}

/**
 * 模拟Post\Get请求  file_get_contents方式
 *
 * @param string 	$url 	执行请求的Url
 * @param mixed	$post_data 表单参数
 * @param string	$Method 请求方法 post / get
 * @param int	$timeOut 等待时间 s
 * @return string 结果字串
 */

function send_post($url, $post_data,$Method='POST',$timeOut = 10) {

    $options = array(
        'http' => array(
            'method' => $Method,
            'header' => 'Content-type:application/x-www-form-urlencoded;charset=utf-8 ',
            'content' => $post_data,
            'timeout' => $timeOut
        ));
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result;
}
/**
 * DIY通知接口
 * 一般用于支付通知
 *
 * @author zxq 2016.06.17
 * @param string 	$Url 	执行请求的Url
 * @param mixed	$Params 表单参数
 * @param string 	$Str 	通知成功时返回的字串
 * @param string	$Method 请求方法 post / get
 * @param int	$TimeOut 等待时间 s
 * @return 1 成功
 */

function notify($Url, $Params, $Str='success', $Method='post', $TimeOut=10){
	$rs = 0;
	$Res = sendHttp($Url, $Params, $Method, $TimeOut);//模拟请求
	// if(is_array($Params)){
		// file_put_contents('/tmp/log/notify.txt', createLinkstring(argSort($Params))."\r\n--".$Res."\r\n",FILE_APPEND);
	// }else{
		// file_put_contents('/tmp/log/notify.txt', $Params."\r\n".$Res."\r\n++",FILE_APPEND);
	// }
	$pos = stripos($Res,$Str);
	if ($pos !== false) {
		$rs = 1;
	} else {
		$rs = 0;
		file_put_contents('/tmp/log/notifyerr_'.date('y-m-d').'.txt', microDate("y-m-d H:i:s.x")." 通知失败\r\n通知URL:{$Url}\r\n传参(Json之):".json_encode($Params)."\r\n返回:".$Res."\r\n\r\n",FILE_APPEND);
	}
	return $rs;
}

/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstring($para) {
	$arg  = "";
	while (list ($key, $val) = each ($para)) {
		$arg.=$key."=".$val."&";
	}
	//去掉最后一个&字符
	$arg = substr($arg,0,count($arg)-2);
	//$arg = str_replace('+', '%2B', $arg);
	//如果存在转义字符，那么去掉转义
	if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

	return $arg;
}

/**
 * 除去数组中的空值和签名参数
 * @param $para 签名参数组
 * @param $notSign 不参与签名的键
 * @param $filter 是否去除空值的键
 * return 去掉空值与签名参数后的新签名参数组
 */
function paraFilter($para,$notSign,$filter=true) {
	$para_filter = array();
	while (list ($key, $val) = each ($para)) {
		if($filter){
			if(in_array($key, $notSign) || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}else{
			if(in_array($key, $notSign))continue;
			else	$para_filter[$key] = $para[$key];
		}
	}
	return $para_filter;
}

/**
 * 对数组排序
 * @param $para 排序前的数组
 * return 排序后的数组
 */
function argSort($para) {
	ksort($para);
	reset($para);
	return $para;
}

/**
 * 格式化金钱
 * @param $amount 金额数量
 * @param $type 1 分转元 2 元转分
 * return 格式化后的数字字符串
 */
function moneyFormat($amount,$type = 1) {
	switch ($type) {
		case 1://分转元
			$amount = number_format($amount*0.01, 2, '.', '');
			break;
		case 2://元转分
			$amount = round($amount*100);
			break;
		default://分转元
			$amount = number_format($amount*0.01, 2, '.', '');
			break;
	}
	return $amount;
}

/**
 * csv输出下载
 * @author zxq
 */
function export_csv($filename,$data) {
	header("Content-type:text/csv");
	header("Content-Disposition:attachment;filename=".$filename);
	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
	header('Expires:0');
	header('Pragma:public');
	echo $data;
}

/**
 * 获取当前带微秒的日期时间
 * 使用方法：microDate("y-m-d H:i:s.x");
 * @author zxq
 */
function microDate($tag){
    list($usec,$sec)=explode(" ", microtime());
    $now_time=((float)$usec+(float)$sec);
    list($usec,$sec)=explode(".", $now_time);
    $date=date($tag,$usec);
    return str_replace('x', $sec, $date);
}

/**
 * URL_safe版本的base64加密
 * @author zxq
 */
function base64url_encode($data) { 
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
}

/**
 * URL_safe版本的base64解密
 * @author zxq
 */
function base64url_decode($data) { 
  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
}

/*
 * 通过生日返回年龄
 * birthday('1990-01-01');
 * return string
 * */
function birthday($birthday){
	if(!$birthday)
		return '未知';
    list($year,$month,$day) = explode("-",$birthday);
    $year_diff = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff  = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0)
        $year_diff--;
    return (string)$year_diff;
}


