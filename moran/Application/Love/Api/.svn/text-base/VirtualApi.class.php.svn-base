<?php
// +----------------------------------------------------------------------
// | GamePlat [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.yongshihuyu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Love\Api;
use Love\Api\Api;
use Addons\Pay\ThinkPay\Pay;

/**
 * 默认控制器
 * @author zxq
 */
class VirtualApi extends Api {

    /**
     * 默认方法 介绍接口用途
     * @author zxq
     */
    public function index() {
    	echo "一些虚拟接口，用于模拟渠道sdk客户端、渠道sdk服务端、游戏客户端、游戏服务端等相关接口或者请求";
    }
	
	/**
     * 参数配置接口文档
     * http://www.isgcn.com/api/love/public/paramter.api
     *@param  paramterVer  参数配置版本号
     * 本接口地址：http://www.isgcn.com/api/love/virtual/paramter/sid/3.api
     * */
    public function paramter(){
        $params = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            //"ptname"=> 'lunar',//不传返回全部，传错不反配置 传对则只反所传字段的配置
            //"ptname"=> 'area',
            "aid"=>1,
            "imeil"=> "869895026016108",//可空
            'uuid'=>'',//可空
            "ip"=> "192.168.1.73",//可空
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
		$apiFile = '/Love/Public/paramter.api';
        $iDomain = I('server.HTTP_HOST');
		$p = '/^api(.*)/';
		if (preg_match($p, $iDomain)) {
		    $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
		}elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
        	$iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
        	//$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
        	$iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;
		$onLine = C('AJAX_API_DOMAIN').$apiFile;
		echo '<title>获取参数默认配置接口说明文档</title>';
		echo '当前接口地址:'.$Url;
		echo '<br />线上接口地址:'.$onLine;
		echo '<br /><br />传参说明:<br />';
		echo '<pre>
            "sid"=>$this->sid,//不可空
            "paramterVer" => 0,//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            //"ptname"=> "lunar",//不传返回全部，传错不反配置 传对则只反所传字段的配置
            "ptname"=> "area",//area时，返回地区列表
            "aid"=>1,//只有ptname=area时，该字段才有效，用于列出当前省份下的全部市
            "imeil"=> "869895026016108",//可空
            "uuid"=>"",//可空
            "ip"=> "192.168.1.73",//可空
		</pre>';
		echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
		echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
		$array = json_decode($res);
		dump($array);
    }
	
	/**
	 * 网站信息接口文档
     * http://www.isgcn.com/api/love/public/siteInfo.api
     * 本接口地址：http://www.isgcn.com/api/love/virtual/siteInfo.api
	 */
	public function siteInfo(){
        $params = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
		$apiFile = '/Love/Public/siteInfo.api';
        $iDomain = I('server.HTTP_HOST');
		$p = '/^api(.*)/';
		if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
		    $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
		}elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
        	$iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
        	//$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
        	$iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
		$onLine = C('AJAX_API_DOMAIN').$apiFile;
		echo '<title>获取网站信息接口说明文档</title>';
		echo '当前接口地址:'.$Url;
		echo '<br />线上接口地址:'.$onLine;
		echo '<br /><br />传参说明:<br />';
		echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
		</pre>';
		echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
		echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
		$array = json_decode($res);
		dump($array);exit;
	}

    /**
     * 网站信息接口文档
     * http://www.isgcn.com/api/love/public/siteInfo.api
     * 本接口地址：http://www.isgcn.com/api/love/virtual/siteInfo.api
     */
    public function agreement(){
        $params = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/Public/agreement.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>获取注册协议接口文档</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
		</pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

	/**
     * 模拟sdk客户端注册
	 * 注册接口地址:http://www.isgcn.com/api/game/user/register.api
	 *
	 * 本接口地址:http://www.momoran.cn/api/love/virtual/register/sid/1.api
	 * 登录渠道sdk之后会执行该接口的操作，该接口返回聚合的token
     * @author zxq
     */
	public function register() {
//        $params = array(
//            "sid"=>$this->sid,//不可空
//            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
//            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
//            "imeil"=> "869895026016108",//可空
//            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
//            "ip"=> "192.168.1.73",//可空
//            "gender"=> "1",//不可空  1 == 男  -1 == 女
//            "age"=> "25",//不可空  25
//        );
        $params = array(
            "gender" => -1,
            "age" => 0,
            "imeil" => '869895026016108',
            "uuid" => '1efb55db0b545766ed940db8c32a65b37cc06ae5',
            "deviceinfo" => 'iPhone 7',
            "paramterVer" => '0',
            "sid" => '1',
            "ip" => '192.168.1.73',
            "sign" => '34e1857a791feebecf8fb26fb1a4f168',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/User/register.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>用户注册接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "1",//性别，必传-1女 1男
            "age"=> "25",//年龄，必传
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

	/**
	 * 模拟sdk客户端上传头像接口
	 * 注册接口地址:http://www.isgcn.com/api/game/user/avatar.api
	 *
	 * 本接口地址:http://www.momoran.cn/api/love/virtual/avatar/sid/1.api
	 * 登录渠道sdk之后会执行该接口的操作，该接口返回聚合的token
	 * @author zxq
	 */
	public function avatar() {

		$params = array(
            "sid" => $this->sid,
			"uid" => 21,
            "avatar_file" => 'file stream',
		);
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据

		$apiFile = '/Love/User/avatar.api';
		$iDomain = I('server.HTTP_HOST');
		$p = '/^api(.*)/';
		if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
			$iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
		}elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
			$iDomain = 'http://'.I('server.HTTP_HOST').'/api';
		}else{
			//$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
			$iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
		}
		$Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
		$onLine = C('AJAX_API_DOMAIN').$apiFile;
		echo '<title>用户头像修改接口</title>';
		echo '当前接口地址:'.$Url;
		echo '<br />线上接口地址:'.$onLine;
		echo '<br /><br />传参说明:<br />';
		echo '<pre>
            "sid" => "1",
            "uid"=> "21",
            "avatar_file" => "file stream",
        </pre>';
		echo '<br /><br />传参例子（Post请求均可）:<br />';
		dump($Params);
		$res = sendHttp($Url, $Params,'post');
		echo '<br />接口返回:<br />';
		echo $res;
		echo '<br /><br />返回Json解析结果:<br />';
		$array = json_decode($res);
		dump($array);exit;
	}

	/**
	 * 模拟sdk客户端上传头像接口
	 * 注册接口地址:http://www.isgcn.com/api/game/user/photo.api
	 *
	 * 本接口地址:http://www.momoran.cn/api/love/virtual/photo/sid/1.api
	 * 登录渠道sdk之后会执行该接口的操作，该接口返回聚合的token
	 * @author zxq
	 */
	public function photo() {

		$params = array(
            "sid" => $this->sid,
			"uid" => 21,
            "photo_file" => 'file stream',
		);
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据

		$apiFile = '/Love/User/photo.api';
		$iDomain = I('server.HTTP_HOST');
		$p = '/^api(.*)/';
		if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
			$iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
		}elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
			$iDomain = 'http://'.I('server.HTTP_HOST').'/api';
		}else{
			//$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
			$iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
		}
		$Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
		$onLine = C('AJAX_API_DOMAIN').$apiFile;
		echo '<title>用户相册上传接口</title>';
		echo '当前接口地址:'.$Url;
		echo '<br />线上接口地址:'.$onLine;
		echo '<br /><br />传参说明:<br />';
		echo '<pre>
            "sid" => "1",
            "uid"=> "21",
            "photo_file" => "file stream",
        </pre>';
		echo '<br /><br />传参例子（Post请求均可）:<br />';
		dump($Params);
		$res = sendHttp($Url, $Params,'post');
		echo '<br />接口返回:<br />';
		echo $res;
		echo '<br /><br />返回Json解析结果:<br />';
		$array = json_decode($res);
		dump($array);exit;
	}
	
	/**
	 * 模拟sdk客户端上传头像接口
	 * 注册接口地址:http://www.isgcn.com/api/game/user/photo.api
	 *
	 * 本接口地址:http://www.momoran.cn/api/love/virtual/photo/sid/1.api
	 * 登录渠道sdk之后会执行该接口的操作，该接口返回聚合的token
	 * @author zxq
	 */
	public function myPhoto() {

		$params = array(
            "sid" => $this->sid,
			"uid" => 1,
            "page" => '1',
            "offset" => '10',
		);
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据

		$apiFile = '/Love/User/myPhoto.api';
		$iDomain = I('server.HTTP_HOST');
		$p = '/^api(.*)/';
		if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
			$iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
		}elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
			$iDomain = 'http://'.I('server.HTTP_HOST').'/api';
		}else{
			//$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
			$iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
		}
		$Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
		$onLine = C('AJAX_API_DOMAIN').$apiFile;
		echo '<title>用户相册上传接口</title>';
		echo '当前接口地址:'.$Url;
		echo '<br />线上接口地址:'.$onLine;
		echo '<br /><br />传参说明:<br />';
		echo '<pre>
            "sid" => "1",
            "uid"=> "21",
            "page" => "1",//页码
            "offset" => "1",//每页张数
        </pre>';
		echo '<br /><br />传参例子（Post请求均可）:<br />';
		dump($Params);
		$res = sendHttp($Url, $Params,'post');
		echo '<br />接口返回:<br />';
		echo $res;
		echo '<br /><br />返回Json解析结果:<br />';
		$array = json_decode($res);
		dump($array);exit;
	}

	/**
     * 模拟聚合sdk客户端向聚合服务端发送渠道sdk登录后返回的sid等数据进行验证
	 * 登录接口地址:http://www.isgcn.com/api/game/user/login.api
	 * 接口必传sdk和gid 或者 地址必配sdk和gid 或者 sdk放地址gid放接口参数
	 *
	 * 本接口地址:http://www.isgcn.com/api/game/virtual/login/gid/1.api
	 * 登录渠道sdk之后会执行该接口的操作，该接口返回聚合的token
     * @author zxq
     */
	public function login() {
        $pra = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone7",//13716109284|android5.1
            "device"=> "iPhone7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
            "username"=> "admin",//可空  1 == 男
            //"uid" => '705',
            "password"=> "admin",//可空  1 == 男
        );
        $pra['sign'] = $this->createSign($pra);//创建签名sign
        $Params = createLinkstring(argSort($pra));//生成Post拼接数据
        $apiFile = '/Love/User/login.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        dump($Url);//dump(cookie());
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>用户登录接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "username"=> "admin",//不可空 用户id
            "password"=> "admin",//不可空 用户密码
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($Url);
        dump($pra);dump(http_build_query($pra));
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

	/**
     * 获取用户协议接口
	 * 注册接口地址:http://www.isgcn.com/api/love/user/vipInfo.api
	 *
	 * 本接口地址:http://www.isgcn.com/api/love/virtual/vipInfo/sid/1.api
	 * 登录渠道sdk之后会执行该接口的操作，该接口返回聚合的token
     * @author zxq
     */
	public function vipInfo() {
        $para = array(
			'uid' => 4770,
			'sid' => $this->sid,
			'ip' => get_client_ip(),
			'imeil' => '867516022231357',
			'deviceinfo' => "iPhone7",
            //'returnType'=> 'xml',
        );
        $para = array_merge($para,$this->requestData);//合并参数 把gid sdk合并进来
		$para['sign'] = $this->createSign($para,'key');
		dump($para);
		$Params = createLinkstring(argSort($para));//生成Post拼接数据
        $apiFile = '/Love/User/vipInfo.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        dump($Url);
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>用户vip检测接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo "<pre>
            'uid' => 1,
			'sid' => sid,
			'ip' => get_client_ip(),
			'imeil' => '867516022231357',
			'deviceinfo' => 'iPhone7',
        </pre>";
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($Url);
        dump($para);dump(http_build_query($para));
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

	/**
     * 获取用户协议接口
	 * 注册接口地址:http://www.isgcn.com/api/game/user/about.api
	 *
	 * 本接口地址:http://www.isgcn.com/api/game/virtual/about/gid/1.api
	 * 登录渠道sdk之后会执行该接口的操作，该接口返回聚合的token
     * @author zxq
     */
	public function About() {
        $para = array(
			'aid' => 2,
			'gid' => $this->gid,
			'ip' => get_client_ip(),
			'imeil' => '867516022231357',
			'deviceinfo' => getOSInfo(),
            //'returnType'=> 'xml',
        );
        $para = array_merge($para,$this->requestData);//合并参数 把gid sdk合并进来
		$para['sign'] = $this->createSign($para,'key');
		dump($para);
		$Params = createLinkstring(argSort($para));//生成Post拼接数据
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/user/about.api';
        $str = sendHttp($Url,$Params);
		echo $str;
		$array = json_decode($str);
		dump($array);
        exit;
    }

	

	/**
     * 模拟游戏客户端验证用户token同时登录游戏
	 * 用户token验证接口:http://www.isgcn.com/api/game/user/verify.api
	 * 接口必传sdk和gid 或者 地址必配sdk和gid 或者 sdk放地址gid放接口参数
	 * 本接口地址:http://www.isgcn.com/api/game/virtual/verify/gid/1.api
	 * 游戏客户端拿到聚合给的token之后会执行该操作，该接口返回验证成功或者失败
     * @author zxq
     */
	public function verify(){
		$post = array(
			'userID'=>'27',
			'userName'=>'ieras_qq',
			'gid'=>$this->gid,
			'loginTime'=>1472646181,
			'extension'=>'[]',
			'token'=>'6887d3fad8481c1fd43733ebf92cd193',
		);
		dump($post);
		//此处逻辑为模拟写法，游戏方自己可以重写
		$post['sign'] = $this->createSign($post,'secret');//使用secret作为key创建签名
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/user/verify.api';
        $str = sendHttp($Url,$post);
		echo $str;
        $array = json_decode($str,true);
		dump($array);exit;
		if($array['code'] == 1){
			/*游戏方自己处理
			 * 验证成功后，返回游戏客户端验证结果
			*/
			$this->ajaxReturn(returnInfo('1','游戏登录成功!',$array['data'],$this->infoType),$this->returnType);
		}else{
			$this->ajaxReturn(returnInfo('-4','游戏登录失败!',$array['msg'],$this->infoType),$this->returnType);
		}
	}

	/**
     * 模拟游戏客户端验证用户token同时登录游戏
	 * 用户token验证接口:http://www.isgcn.com/api/game/user/verify.api
	 * 接口必传sdk和gid 或者 地址必配sdk和gid 或者 sdk放地址gid放接口参数
	 * 本接口地址:http://www.isgcn.com/api/game/virtual/loginGame/gid/1.api
	 * 游戏客户端拿到聚合给的token之后会执行该操作，该接口返回验证成功或者失败
     * @author zxq
     */
	public function loginGame(){
		//$post = array('userID'=>'6','token'=>'67cf009c71a50a3b7fa3d2493a97bd40','gid'=>$this->gid,);
		//dump($post);
		if($this->verifySign()){
			//此处逻辑为模拟写法，游戏方自己可以重写
			$post = $this->requestData;
			$verify['userID'] = $post['userID'];
			$verify['token'] = $post['token'];
			$verify['gid'] = $post['gid'];
			$verify['sign'] = $this->createSign($verify,'secret');//使用secret作为key创建签名
	        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/user/verify.api';
	        $str = sendHttp($Url,$verify);
	        $array = json_decode($str,true);
			//dump($array);
			//echo $str;
			if($array['code'] == 1){
				/*游戏方自己处理
				 * 验证成功后，返回游戏客户端验证结果
				*/
				$this->ajaxReturn(returnInfo('1','游戏登录成功!',$array['data'],$this->infoType),$this->returnType);
			}else{
				$this->ajaxReturn(returnInfo('-4','游戏登录失败!',$array['msg'],$this->infoType),$this->returnType);
			}
		}else{
			$this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
		}
		exit;
		//$str = gid=1sdk=2token=32e88db59a79383d81703df847e0935cuserID=19100000001
	}

	/**
     * 模拟聚合sdk客户端获取聚合订单接口
	 * 获取聚合订单接口:http://www.isgcn.com/api/game/wealth/payMent.api
	 * 接口必传sdk和gid 或者 地址必配sdk和gid 或者 sdk放地址gid放接口参数
	 * 本接口地址:http://www.isgcn.com/api/game/virtual/payMent/gid/1.api
	 * 聚合sdk客户端再调渠道sdk客户端支付显示页面时调用该接口
     * @author zxq
     */
	public function payMent(){
		//模拟返回数据
		//$str = '{"code":"1","msg":"\u83b7\u53d6\u8ba2\u5355\u6210\u529f","data":{"orderId":"160705161409491019708002","extension":"DIY\u53c2\u6570"}}';
		//$array = json_decode($str);
		//dump($array);
		//$this->ajaxReturn(returnMsg('1','获取订单成功',$array->data),$this->returnType);
		//exit;
		$para = array(
			'aid' => 2,//推广渠道id
			'userID' => '21',
			'productname' => '完美世界',//商品名称
			'productdesc' => '60元宝',//商品描述
			'amount' => '1',//(price)商品总额,单位:分
			'money' => '1',//(price)消耗余额,单位:分
			'yxb' => '0',//(price)消耗游戏币,单位:分
			'roleid' => '23',//角色id/名称
			'gid' => $this->gid,//游戏id
			'server_name' => '三服',//游戏服务器
			'ip' => '127.0.0.1',
			'imeil' => '867516022231353',
			'deviceinfo' => getOSInfo(),
			'backurl' => '',//(payNotifyUrl)通知地址，传则优先，不传则用配置
			'note' => 'DIY参数',//(extension)游戏cp的订单号或者cp的其它自定义标识信息，聚合会原样回调给游戏cp
			//'pay_type'=> 'money',
			//'pay_type'=> 'alipay',
			'pay_type'=> 'money',
			//'pay_type'=> 'nowpay',//wxpay
			//'channel_type'=>'weixin',//第三方支付锁定支付时候用
		);
		$para['sign'] = $this->createSign($para);//创建签名sign
		dump($para);
		$Params = createLinkstring(argSort($para));//生成Post拼接数据
		$Url = 'http://'.I('server.HTTP_HOST')."/api/game/wealth/payMent/returnType/{$this->returnType}/infoType/{$this->infoType}.api";
		$res = sendHttp($Url, $Params,'post');
		echo $res;
		$array = json_decode($res);
		dump($array);exit;
	}

	/**
     * 模拟聚合sdk客户端获取聚合订单接口
	 * 获取聚合订单接口:http://www.isgcn.com/api/game/wealth/recharge.api
	 * 接口必传sdk和gid 或者 地址必配sdk和gid 或者 sdk放地址gid放接口参数
	 * 本接口地址:http://www.isgcn.com/api/game/virtual/recharge/gid/1.api
	 * 聚合sdk客户端再调渠道sdk客户端支付显示页面时调用该接口
     * @author zxq
     */
	public function recharge(){
		//模拟返回数据
		//$str = '{"code":"1","msg":"\u83b7\u53d6\u8ba2\u5355\u6210\u529f","data":{"orderId":"160705161409491019708002","extension":"DIY\u53c2\u6570"}}';
		//$array = json_decode($str);
		//dump($array);
		//$this->ajaxReturn(returnMsg('1','获取订单成功',$array->data),$this->returnType);
		//exit;
		$para = array(
			'aid' => 2,//推广渠道id
			'userID' => '21',
			'productname' => '充值',//商品名称
			'productdesc' => '充值',//商品描述
			'money' => '1',//(price)消耗余额,单位:分
			'gid' => $this->gid,//游戏id
			'ip' => '127.0.0.1',
			'imeil' => '867516022231353',
			'deviceinfo' => getOSInfo(),
			//'pay_type'=> 'money',
			'pay_type'=> 'nowpay',//wxpay
			'channel_type'=>'weixin',//第三方支付锁定支付时候用
		);
		$para['sign'] = $this->createSign($para);//创建签名sign
		dump($para);
		$Params = createLinkstring(argSort($para));//生成Post拼接数据
		$Url = 'http://'.I('server.HTTP_HOST')."/api/game/wealth/recharge/returnType/{$this->returnType}/infoType/{$this->infoType}.api";
		$res = sendHttp($Url, $Params,'post');
		echo $res;
		$array = json_decode($res);
		dump($array);exit;
	}

	/**
     * 模拟渠道sdk服务端像聚合发送支付通知请求
	 * 接收通知地址:http://www.isgcn.com/api/aggregation/sync/v1/sdk/2/gid/1.api
	 * 地址必须配置sdk和gid,接收通知地址在验证通过后会继续通知游戏通知接口如本类中的模拟接口gameSync
	 * http://域名/接口入口/模块名称/接口名称/接口大版本/聚合渠道参数名/聚合渠道参数值/游戏参数名/游戏id.api
	 * 本接口地址:http://www.isgcn.com/api/game/virtual/sync/sdk/2/gid/1.api
	 * 该接口地址要配置到渠道sdk对应游戏的回调地址里面
	 * 用户支付完成后，渠道会执行该接口的操作
     * @author zxq
     */
	public function sync() {
		$get = $this->requestData;
		//dump($get);exit;
		//$Params = 'orderid=14528613738453292&username=d531238826&gameid=1&roleid=roleid&serverid=1&paytype=weixin&amount=1&paytime=1452861376&attach=160623102048598485008002&sign=e399777292666616bc4fe564cd104f7f';
		$Params = 'orderid=14672742189074472&username=qqqqqq&gameid=281&roleid=roleid&serverid=%E6%9C%8D%E5%8A%A1%E5%99%A8id1&paytype=money&amount=0.01&paytime=1467274216&attach=160630160441957559708002&sign=414196f7c3b8c6e559e515c89ecc8b9e';
		$Params = '{"discount":"0.00","payment_type":"1","subject":"6\u5143\u5b9d","trade_no":"2016103121001004200222918594","buyer_email":"18610773198","gmt_create":"2016-10-31 19:13:51","notify_type":"trade_status_sync","quantity":"1","out_trade_no":"161031040011913409499954","seller_id":"2088221532419380","notify_time":"2016-10-31 19:18:45","body":"\u5929\u9f99\u516b\u90e8","trade_status":"WAIT_BUYER_PAY","is_total_fee_adjust":"Y","total_fee":"0.01","seller_email":"fangcheng@yongshihuyu.com","price":"0.01","buyer_id":"2088802760158207","notify_id":"ed2a8d7f417f900ada5a0270b837f06hjm","use_coupon":"N","sign_type":"RSA","sign":"NFYKp4Az\/piIiEKE\/Taw2yeTRq7Iu9Gqh7aTrmwlmRRxkFKSgt01vK6QWBLkSm2wuJggDDcUQFWpbGxKlEiRcA47+jRgMlVhXt93lTkJCESXwPp2+IQzzA0wRZS6CpGEQ9skBqWekgU7pl+64g8lzgEbP1YiWaFsU6wU9b9tZHo="}';
		//$Params = '{"discount":"0.00","payment_type":"1","subject":"\u52c7\u58eb\u4e92\u5a31\u4f59\u989d\u5145\u503c","trade_no":"2016080121001004200226008329","buyer_email":"18610773198","gmt_create":"2016-08-01 17:19:09","notify_type":"trade_status_sync","quantity":"1","out_trade_no":"160801171848001029806001","seller_id":"2088221532419380","notify_time":"2016-10-31 18:58:06","body":"\u52c7\u58eb\u4e92\u5a31\u4f59\u989d\u5145\u503c","trade_status":"TRADE_FINISHED","is_total_fee_adjust":"N","total_fee":"0.01","gmt_payment":"2016-08-01 17:19:19","seller_email":"fangcheng@yongshihuyu.com","gmt_close":"2016-10-31 17:19:32","price":"0.01","buyer_id":"2088802760158207","notify_id":"476b54b5052b98b23f2aa027095db18hjm","use_coupon":"N","sign_type":"MD5","sign":"971894136909a5a01cc8f72720d822f4"}';
		//$Params = str_replace('+', '%2B', $Params);
		$Url = 'http://'.I('server.HTTP_HOST').'/api/aggregation/sync/v1/sdk/'.$get['sdk'].'/gid/'.$get['gid'].'.api';
		$Params = json_decode($Params);
		$Url = 'http://'.I('server.HTTP_HOST').'/recharge/index/notify/apitype/alipay/method/notify/gid/14';
		dump($Params);
		$res = sendHttp($Url, http_build_query($Params), $Method='post');
		echo $res;
		exit;
    }

	/**
     * 模拟游戏服务端接收聚合服务器支付成功的通知
	 *
	 * 接口必传sdk和gid 或者 地址必配sdk和gid 或者 sdk放地址gid放接口参数
	 * 本接口地址:http://www.isgcn.com/api/aggregation/virtual/gameSync.api
	 * 该接口地址要配置到聚合sdk服务端对应游戏的回调里面
	 * 聚合接收渠道回调后，然后通知游戏，游戏接到通知时执行该接口操作
     * @author zxq
     */
	public function gameSync(){
		/*
		$json = '{
		    "amount": "1",
		    "freeAmount": "",
		    "orderID": "160630163259555310208002",
		    "payType": "money",
		    "sdk": "2",
		    "sdkOrder": "14672756380552545",
		    "sdkUser": "qqqqqq",
		    "sdkUserID": "",
		    "userID": "24",
		    "note": "",
		    "productName": "魔神",
		    "gid": "1",
		    "server": "服务器id1",
		    "payTime": "1467275579",
		    "sign": "e9c7e51c1c17ce1727045e40c38047b1"
		}';
		*/
		$this->requestData = json_decode($json,TRUE);
		if($this->verifySign('secret')){
			//校验通过，发放游戏道具(币)
			/*代码逻辑游戏方提供，逻辑处理完务必返回约定的成功标识(如:"success")，并且务必对已经发放道具的订单做标识处理，
			 * 严防我方重复通知时导致游戏币发重的情况
			*/
			echo "success";
		}else{
			echo "Fail";
		}
		//file_put_contents($this->sdks.'1.txt', json_encode($_POST)."\r\n",FILE_APPEND);
		//dump($this->requestData);
        exit;
	}

	/**
     * 模拟聚合sdk客户端执行退出操作
	 * 聚合sdk退出接口:http://www.isgcn.com/api/game/user/logOut.api
	 * 接口必传sdk和gid 或者 地址必配sdk和gid 或者 sdk放地址gid放接口参数
	 * 本接口地址:http://www.isgcn.com/api/game/virtual/logOut/gid/1.api
	 * 当用户退出游戏时调用该接口
     * @author zxq
     */
	public function logOut(){
		$para = array(
			'userID' => '27',
			'gid' => $this->gid,//游戏id
			//'ip' => '59.38.98.134',
			//'imeil' => '865453021050344',
			//'device' => '3',
			//'deviceinfo' => '13535790158|android4.4.2',
		);
		$para['sign'] = $this->createSign($para);//创建签名sign
		$Params = createLinkstring(argSort($para));//生成Post拼接数据
		$Url = 'http://'.I('server.HTTP_HOST').'/api/game/user/logOut.api';
		$res = sendHttp($Url, $Params,'post');
		dump($para);
		echo $res;
		$array = json_decode($res);
		dump($array);exit;
	}

   /**
    * 模拟获取用户余额列表
    * 获取用户约列表接口 http://www.isgcn.com/api/game/user/uCenter.api
    *
    * 本接口地址：http://www.isgcn.com/api/game/virtual/uCenter.api
    * */
    public function uCenter(){
        $params = array(
            'aid' => '1',
            'gid' => $this->gid,
            'userID' => '1595',
            'ip' => '59.38.98.134',
            'imeil' => '351539070341971',
            'deviceinfo' => '13535790158|android4.4.2',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/wealth/uCenter.api';
        $res = sendHttp($Url, $Params,'post');
        dump($res);
    }

    /**
     * 模拟获取用户余额列表
     * 获取用户约列表接口 http://www.isgcn.com/api/game/user/rechargeList.api
     * @param $aid  渠道id
     * @param $gid  游戏id
     * @param   userId 用户id
     * 本接口地址：http://www.isgcn.com/api/game/virtual/rechargeList.api
     * */
    public function rechargeList(){
        $params = array(
            'aid' => 'default',//渠道id
            'gid' => $this->gid,//游戏id
            'userID' => '1',//用户id
            'page' => 1,//页码表示 上一页 1 2 3 4 5 下一页
            'offset' => 10,// 条数  表示每页显示多少条
            'ip' => '59.38.98.134',//ip
            'imeil' => '351539070341971',//手机imeil
            'deviceinfo' => '13535790158|android4.4.2',//设备信息
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/wealth/rechargeList.api';
        $res = sendHttp($Url, $Params,'post');
        dump($res);
    }

    /**
     * 模拟获取用户余额明细
     * 获取用户约列表接口 http://www.isgcn.com/api/game/wealth/rechargeDetail.api
     * @param $aid  渠道id
     * @param $gid  游戏id
     * @param   orderId 订单编号
     * 本接口地址：http://www.isgcn.com/api/game/virtual/rechargeDetail.api
     * */
    public function rechargeDetail(){
        $params = array(
            'aid' => '1',
            'gid' => $this->gid,
            'orderId' => '161008040011119239957102',
            'ip' => '59.38.98.134',
            'imeil' => '351539070341971',
            'deviceinfo' => '13535790158|android4.4.2',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/wealth/rechargeDetail.api';
        $res = sendHttp($Url, $Params,'post');
        dump($res);
    }


    /**
     * 模拟获取用户游戏币列表
     * 获取用户约列表接口 http://www.isgcn.com/api/game/Wealth/yxb.api
     * @param $aid  渠道id
     * @param $gid  游戏id
     * @param   userId 用户id
     * 本接口地址：http://www.isgcn.com/api/game/virtual/yxb.api
     * */
    public function yxb(){
        $params = array(
            "gid"=>"1",
            "aid"=>"default",
            "userID"=>"225",
            "page"=>1,
            "offset"=>5,
            'ip' => '59.38.98.134',
            'imeil' => '351539070341971',
            'deviceinfo' => '13535790158|android4.4.2',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/Wealth/yxb.api';
        $res = sendHttp($Url, $Params,'post');
        dump($res);
    }

    /**
     * 模拟获取用户游戏币详情
     * 获取用户约列表接口 http://www.isgcn.com/api/game/Wealth/yxbDetail.api
     * @param $aid  渠道id
     * @param $gid  游戏id
     * @param   id  详情id
     * 本接口地址：http://www.isgcn.com/api/game/virtual/yxbDetail/gid/1.api
     * */
    public function yxbDetail(){
        $params = array(
            'aid' => '1',
            'gid' => $this->gid,
            'id' => '1',
            'ip' => '59.38.98.134',
            'imeil' => '351539070341971',
            'deviceinfo' => '13535790158|android4.4.2',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/Wealth/yxbDetail.api';
        $res = sendHttp($Url, $Params,'post');

    }

    /**
     * 模拟消费订单
     * 获取用户约列表接口 http://www.isgcn.com/api/game/user/payList.api
     * @param $aid  渠道id
     * @param $gid  游戏id
     * @param $userid  详情id
     * 本接口地址：http://www.isgcn.com/api/game/virtual/payList/gid/1.api
     * */
    public function payList(){
        $params = array(
            'aid' => '1',
            'gid' => $this->gid,
            'userID' => '23',
            'page' => '1',//页码表示 上一页 1 2 3 4 5 下一页
            'offset' => '5',// 条数  表示每页显示多少条
            'ip' => '59.38.98.134',
            'imeil' => '351539070341971',
            'deviceinfo' => '13535790158|android4.4.2',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/Wealth/payList.api';
        $res = sendHttp($Url, $Params,'post');
        var_dump($res);
    }

    /**
     * 模拟消费订单详情
     * 获取用户约列表接口 http://www.isgcn.com/api/game/Wealth/payDetail.api
     * @param $aid  渠道id
     * @param $gid  游戏id
     * @param   id  详情id
     * 本接口地址：http://www.isgcn.com/api/game/virtual/payDetail/gid/1.api
     * */
    public function payDetail(){
        $params = array(
            'aid' => '1',
            'gid' => $this->gid,
            'id' => '23',
            'page' => '1',//页码表示 上一页 1 2 3 4 5 下一页
            'offset' => '5',// 条数  表示每页显示多少条
            'ip' => '59.38.98.134',
            'imeil' => '351539070341971',
            'deviceinfo' => '13535790158|android4.4.2',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/Wealth/payDetail.api';
        $res = sendHttp($Url, $Params,'post');
        var_dump($res);
    }

    /**
     * 修改密码
     * 获取修改密码接口 http://www.isgcn.com/api/game/user/resetPass.api
     * @param $aid  渠道id
     * @param $gid  游戏id
     * @param   email  邮箱
     * @param   userID  用户ID
     * @param   password  就密码
     * @param   newPassword  新密码
     * 本接口地址：http://www.isgcn.com/api/love/virtual/resetPass.api
     * */
    public function resetPass(){
        $params = array(
            'sid' => $this->sid,
            'uid' => '9',
            'username' => 'admin',
            'password' => 'admin8848',
            'newPassword' => 'admin8848',
            'ip' => '59.38.98.134',
            'idfa' => '351539070341971',
            'deviceinfo' => '13535790158|android4.4.2',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/love/user/resetPass.api';
        $res = sendHttp($Url, $Params,'post');
		echo $res;
		$array = json_decode($res);
		dump($array);exit;
    }


    /**
     * 修改邮箱接口
     * 获取用户约列表接口 http://www.isgcn.com/api/game/user/resetEmail.api
     * @param $aid  渠道id
     * @param $gid  游戏id
     * @param  userID  用户id
     * @param   email 邮箱
     * 本接口地址：http://www.isgcn.com/api/game/virtual/resetEmail.api
     * */
    public function resetEmail(){
        $params = array(
            'aid' => '1',
            'gid' => $this->gid,
            'userID' => '162',//用户Id
            'email' => '1772764331@qq.com',//邮箱
            'ip' => '59.38.98.134',
            'imeil' => '351539070341971',
            'deviceinfo' => '13535790158|android4.4.2',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/user/resetEmail.api';
        $res = sendHttp($Url, $Params,'post');
        var_dump($res);
    }

    /**
     * 修改邮箱接口
     * 获取用户约列表接口 http://www.isgcn.com/api/game/user/resetPassEmail.api
     * @param $aid  渠道id
     * @param $gid  游戏id
     * @param  userID  用户id
     * @param   email 邮箱
     * 本接口地址：http://www.isgcn.com/api/game/virtual/resetPassEmail.api
     * */
    public function resetPassEmail(){
        $params = array(
            'aid' => '190',
            'gid' => $this->gid,
            'email' => '1772764331@qq.com',//邮箱
            'ip' => '59.38.98.134',
            'imeil' => '351539070341971',
            'deviceinfo' => '13535790158|android4.4.2',
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/user/resetPassEmail.api';
        $res = sendHttp($Url, $Params,'post');
        var_dump($res);
    }

    /**
     * 创建角色
     * 获取用户约列表接口 http://www.isgcn.com/api/game/user/createUser.api
     *@param  roleID 角色id
     *@param  roleName 角色名
     *@param  roleLevel 角色等级
     *@param  roleGendar 角色性别
     *@param  otherInfo  其他扩展信息
     *@param  serverID  区服id
     *@param  serverName 区服名称
     *@param  moneyNum 余额
     * 本接口地址：http://www.isgcn.com/api/game/virtual/saveRoles.api
     * */
    public function saveRoles(){
        $params = array(
            "aid"=>"default",
            "deviceinfo"=> "13716109284||android5.1",
            "gid"=> "1",
            "imeil"=> "869895026016108",
            "ip"=> "192.168.1.73",
            "moneyNum"=>"0.00",
            "otherInfo"=>'"{\"roleNick\":\"\u5c0f\u767d\",\"platformCoin\":\"100\",\"extension\":\"123456\"}"',
            "roleGendar"=>"1",
            "roleID"=> "3",
            "roleLevel"=> "128",
            "roleName"=>"白小白童鞋",
            "userID"=> "156",
            "serverID"=> "117",
            "serverName"=> "太平异道",
            'dataType' => '5'//类型 ：1 选择服务器  2 创建角色  3 进入游戏  4 等级提升 5 退出游戏
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($params);
        $Url = 'http://'.I('server.HTTP_HOST').'/api/game/user/saveRoles.api';
        $res = sendHttp($Url, $Params,'post');
        var_dump($res);
    }

    
	
	/**
     * 新人注册随机5个用户问新人个招呼
     * 本接口地址：http://www.isgcn.com/api/love/virtual/greet/sid/1.api
     * */
    public function greet(){
        $params = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "-1",//不可空  1 == 男  -1 == 女
            "age"=> "25",//不可空  25
            "to_uid"=> "12",//不可空  25
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/user/greet.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>5个问题接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "1",//性别，必传
            "age"=> "25",//年龄，必传
            "to_uid"=> "999",// 收件人 不可空  25
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }
	
	/**
     * 推荐用户接口
     * 本接口地址：http://www.isgcn.com/api/love/virtual/recommend/sid/1.api
     * */
    public function recommend(){
        $params = array(
            "sid"=>$this->sid,//不可空
       		//'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            //"imeil"=> "869895026016108",//可空
            'idfa'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "-1",//不可空  1 == 男  -1 == 女
            "age"=> "25",//不可空  25
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/user/recommend.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>推荐用户接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "1",//性别，必传
            "age"=> "25",//年龄，必传
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    public function userinfo(){
        $params = array(
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "device"=> "4",//不可空
            'idfa'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "19",//不可空  待查看用户UID
            "from_uid"=> "1",//不可空  当前登陆用户UID
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/User/userinfo.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>推荐用户接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "device"=> "4",//不可空
            "idfa"=>"1efb55-db0b5-45766e-d940db8c-32a65b37cc-06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "1",//不可空  待查看用户UID
            "from_uid"=> "1",//不可空  当前登陆用户UID
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    public function nearbyFriends(){
        $params = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "-1",//性别，必传
            "age"=> "25",//年龄，必传
            "page"=>I('page') ? I('page') :1,
            'offset'=>I('offset') ?I('offset'):1,
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/user/nearbyFriends.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>附近好友接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "1",//性别，必传
            "age"=> "25",//年龄，必传
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    public function userByHxId(){
        $params = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
            "hxuuids"=> "5e48fab0-42be-11e7-8f16-656c58488408,5e745070-42be-11e7-b896-67c407a657a3,5ec4ba60-42be-11e7-86a0-cd4ca68205b5,5f0545d0-42be-11e7-8109-9dd070623b64",//环信 uuid
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/user/userByHxId.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>附近好友接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "hxuuids"=> "5e48fab0-42be-11e7-8f16-656c58488408,5e745070-42be-11e7-b896-67c407a657a3,5ec4ba60-42be-11e7-86a0-cd4ca68205b5,5f0545d0-42be-11e7-8109-9dd070623b64",//环信 uuid  必须传递
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回 注意 为了前端更好的解析 返回的json key 为传递时候的 环信 uuid ,未查询到数据则返回null:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    /**
     * 获取附近好友
     */
    public function getKarmaFriends(){
        $params = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "-1",//不可空  25
            "page"=>I('page') ? I('page') :1,
            'offset'=>I('offset') ?I('offset'):15,
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/user/getKarmaFriends.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>获取缘分好友接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "1",//不可空  25
            "page"=> "1",// // 必传~
            "offset" => 15,//非必传，控制每页显示的条数。 默认十五条
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    public function getArea(){
        $params = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
            "aid"=> "392",//不可空  25

        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/Public/getArea.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>获取缘分好友接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "1",//不可空  25
            "page"=> "1",// // 必传~
            "offset" => 15,//非必传，控制每页显示的条数。 默认十五条
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    public function me(){
        $params = array(
            "sid"=>$this->sid,//不可空
            'paramterVer' => '0',//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            'uuid'=>'1efb55db0b545766ed940db8c32a65b37cc06ae5',//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "1",//不可空  25

        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/User/me.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>获取我接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />线上接口地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "gender"=> "1",//不可空  25
            "page"=> "1",// // 必传~
            "offset" => 15,//非必传，控制每页显示的条数。 默认十五条
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    /*
     * 编辑资料
     * */
    public function mfcond(){
        $params = array(
            "sid"=>$this->sid,//不可空
            //"paramterVer" => "0",//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "1",//不可空  25
            "setarea" => "北京",//Ta所在地   c
            "startage" => 18,//Ta最小年林
            "endage" => 55,//Ta最大年龄  cond表中的优先地区
            "startheight" => 30,//Ta最小体重  cond表中的优先地区
            "endheight" => 50,//Ta最大体重  cond表中的优先地区
            "startedu" => 1,//最低学历  cond表中的优先地区
            "salary" => 2,
            "salaryup" => 1
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/User/mfcond.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>交友条件接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />编辑资料地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "1",//不可空  25
            "setarea" => "北京",//Ta所在地   c
            "startage" => 18,// 年龄：18~55
            "endage" => 55,//   年龄： 18~55
            "startheight" => 25,// 体重 25~100
            "endheight" => 100,// 体重 25~100
            "startedu" => 1,// 1:小学 2:初中 3:高中 4:大专 5:本科 6:硕士 7:博士 8:海归
            "salary" => 20000,
            "salaryup" => 1,1:以上 2:以下
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }
	
	/*
     * 获取征友条件资料
     * */
    public function cond(){
        $params = array(
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "device"=> "4",
            "idfa"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "1",//不可空  25
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/User/cond.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>获取交友条件</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "device"=> "4",
            "idfa"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "1",//不可空  25
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    /*
     * 编辑资料
     * */
    public function editInfo(){
        $params = array(
            "sid"=>$this->sid,//不可空
            "paramterVer" => "0",//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "1",//不可空  25
            "monolog" => "爱爱爱爱爱死你了",//内心独白
            "personality" => 4,//个性特征
            "nickname" => "白小",//昵称
            "birthday" => "1988-02-23",//昵称
            "interest" => 1,//兴趣爱好
            "astro" => "水瓶",//星座
            "provinceid" => 1,//省ID     +  籍贯的组合信息
            "cityid" => 2,//市ID         +
            "distid" => 3,//区域ID       +
            "communityid" => 4,//社区ID  +
            "nationprovinceid" => 1, //国籍
            "nationcityid" => 2,//国家ID
            "nationality" => 3,//国家城市ID
            "height" => 185,//身高
            "weight" => 75,//体重，单位KG
            "blood" => 2,//血型
            "education" => 5,//学历
            "jobs" => 2,//专业
            "salary" => 1,//月收入
            "charmparts" => 1,//魅力部位
            "marrystatus" => 1,//婚姻状况 1未婚 2已婚 3离异 4丧偶
            "housing" => 1,//住房情况
            "talive" => 1,//和父母同住
            "havechildren" => 1,//是否要小孩
            "area" => "北京",//所在地
            "areas" => "北京",//Ta所在地   c
            "startage" => 1,//Ta最小年林
            "endage" => 2,//Ta最大年龄  cond表中的优先地区
            "startheight" => 2,//Ta最小体重  cond表中的优先地区
            "endheight" => 2,//Ta最大体重  cond表中的优先地区
            "startedu" => 2,//最低学历  cond表中的优先地区
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/User/editInfo.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>获取我接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />编辑资料地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "paramterVer" => "0",//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "1",//不可空  25
            "monolog" => "爱爱爱爱爱死你了",//内心独白
            "personality" => 4,//个性特征
            "nickname" => "白小白",//昵称
            "interest" => 1,//兴趣爱好
            "astro" => "水瓶",//星座
            "provinceid" => 1,//省ID     +  籍贯的组合信息
            "cityid" => 2,//市ID         +
            "distid" => 3,//区域ID       +
            "communityid" => 4,//社区ID  +
            "nationprovinceid" => 1, //国籍
            "nationcityid" => 2,//国家ID
            "nationality" => 3,//国家城市ID
            "height" => 185,//身高
            "weight" => 75,//体重，单位KG
            "blood" => 2,//血型
            "education" => 5,//学历
            "jobs" => 2,//专业
            "salary" => 1,//月收入
            "charmparts" => 1,//魅力部位
            "marrystatus" => 1,//婚姻状况 1未婚 2已婚 3离异 4丧偶
            "housing" => 1,//住房情况
            "talive" => 1,//和父母同住
            "havechildren" => 1,//是否要小孩
            "setarea" => "北京",//Ta所在地   c
            "startage" => 1,//Ta最小年林
            "endage" => 1,//Ta最大年龄  cond表中的优先地区
            "startedu" => 1,//最低学历  cond表中的优先地区
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    public function message(){
        $params = array(
            "sid"=>$this->sid,//不可空
            "paramterVer" => "0",//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "9",//不可空  25
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/user/message.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>获取系统消息接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />编辑资料地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "paramterVer" => "0",//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "uid"=> "9",//不可空  25
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    public function privateLetter(){
        $params = array(
            "sid"=>$this->sid,//不可空
            "paramterVer" => "0",//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "from_uid"=> "12",//不可空  25
            "to_uid"=> "13",//不可空  25
        );
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/user/privateLetter.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>获取打招呼接口接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />编辑资料地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "paramterVer" => "0",//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "from_uid"=> "12",//不可空  25
            "to_uid"=> "13",//不可空  25
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    public function submitQuery(){
        $params = array(
            "sid"=>$this->sid,//不可空
            "paramterVer" => "0",//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "from_data"=> array(
                array(
                    'from_uid' => 12,
                    'greeting' => '天没崩，地没裂，月没缺，花没落，我就会一直关注着你。',
                    'greetans' => '当然',
                ),
                array(
                    'from_uid' => 13,
                    'greeting' => '可以接受婚前性行为吗？',
                    'greetans' => '当然:当然可以接受了，不然约你干什么？',
                ),
                array(
                    'from_uid' => 38,
                    'greeting' => '你觉得第一次约会可以喝酒吗？',
                    'greetans' => '可以:当然可以啦，有酒才有气氛',
                ),
                array(
                    'from_uid' => 45,
                    'greeting' => '你觉得第一次约会可以喝酒吗？',
                    'greetans' => '可以:当然可以啦，有酒才有气氛',
                ),
                array(
                    'from_uid' => 44,
                    'greeting' => '你觉得第一次约会可以喝酒吗？',
                    'greetans' => '可以:当然可以啦，有酒才有气氛',
                ),
            ),//不可空  25
            "to_uid"=> "13",//不可空  25
        );
		$params['from_data'] = json_encode($params['from_data']);
        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        dump($Params);
        $apiFile = '/Love/user/submitQuery.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>获取打招呼接口接口</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />编辑资料地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "paramterVer" => "0",//全部配置的版本号，当传了ptname该值不做判断
            "deviceinfo"=> "iPhone 7",//13716109284|android5.1
            "imeil"=> "869895026016108",//可空
            "uuid"=>"1efb55db0b545766ed940db8c32a65b37cc06ae5",//可空
            "ip"=> "192.168.1.73",//可空
            "from_uid"=> "12",//不可空  25
            "to_uid"=> "13",//不可空  25
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }

    public function listenMessage(){
        $params = array(
            "sid"=>$this->sid,//不为空
            "message_id"=>"12345",//环信消息ID  不为空
            "from_uid"=>1,//不可空 发送信息者id（环信用户ID）
            "to_uid"=>1,//不可空 接收信息者id （环信用户ID）
            "message" => '{"type":"txt","msg":"\u597d"}',//消息内容（以文本消息txt为例），不可为空
            "is_read"=> '0',//是否被阅读  不可为空  0：未阅读    1：已阅读
            "sort"=> 0,//排序 （可空）
            "status"=> 1,//状态 （可空）
        );

        $params['sign'] = $this->createSign($params);//创建签名sign
        $Params = createLinkstring(argSort($params));//生成Post拼接数据
        $apiFile = '/Love/user/listenMessage.api';
        $iDomain = I('server.HTTP_HOST');
        $p = '/^api(.*)/';
        if (preg_match($p, $iDomain)) {//如果非api访问择永久跳转到www
            $iDomain = 'http://api'.strstr(I('server.HTTP_HOST'), '.');
        }elseif($iDomain !='localhost' && $iDomain != '127.0.0.1'){
            $iDomain = 'http://'.I('server.HTTP_HOST').'/api';
        }else{
            //$iDomain = 'http://'.I('server.HTTP_HOST').dirname(I('server.PHP_SELF')).'/api';
            $iDomain = 'http://'.I('server.HTTP_HOST').I('server.PHP_SELF').'?s=';
        }
        $Url = $iDomain.$apiFile;//dump(\Common\Util\Think\Str::parseAttr(C('love_config.module_url')));
        $onLine = C('AJAX_API_DOMAIN').$apiFile;
        echo '<title>检测消息是否被查看或推送</title>';
        echo '当前接口地址:'.$Url;
        echo '<br />编辑资料地址:'.$onLine;
        echo '<br /><br />传参说明:<br />';
        echo '<pre>
            "sid"=>$this->sid,//不可空
            "message_id"=>"123",//环信消息ID  不为空
            "from_uid"=>1,//不可空  发送信息者id（环信用户ID）
            "to_uid"=>1,//不可空  接收信息者id（环信用户ID）
            "message" => "{"type":"txt","msg":"\u597d"}",//消息内容不可为空（以文本消息txt为例）
            "is_read"=> "0",//是否被阅读  不可为空  0：未阅读    1：已阅读
            "sort"=> 0,//排序（可空）
            "status"=> 1,//状态（可空）
        </pre>';
        echo '<br /><br />传参例子（Post/Get请求均可）:<br />';
        dump($params);
        $res = sendHttp($Url, $Params,'post');
        echo '<br />接口返回:<br />';
        echo $res;
        echo '<br /><br />返回Json解析结果:<br />';
        $array = json_decode($res);
        dump($array);exit;
    }
}