<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Common\Util\Easemob;
/**
 * 聚合平台和渠道平台接口父控制器
 * @author zxq
 */
class ApiController extends Controller {
	/**
	 * 环信参数配置
	 * @author yyl
	 * @email 944677073@qq.com
	 */
	private   $client_id		=	'YXA6f83TAP1dEeaBdXszhlCfmQ';
	private   $client_secret	=	'YXA63-G5bdQ_qTUHD7rNUEqQ4PoOFds';
    private   $org_name			=	'1113170228178682';
	private   $app_name			=	'myapp001';
	public 	  $huanxin		;//环信对象
	
	/*
	private   $client_id		=	'YXA68yai4CN5EeezX2dPeRKXWQ';
	private   $client_secret	=	'YXA610jxXBs4Wph9tXP81kpe-fJq56s';
    private   $org_name			=	'1159170225178142';
	private   $app_name			=	'lovelove';
	public 	  $huanxin		;//环信对象
	*/
	//$infoType 0.明文 1.密文 2.压缩密文 3.json
	public $infoType;
	//returnType 当$infoType为0或者3时生效 目前支持这四种：JSON,XML,JSONP,EVAL
	public $returnType;
	public $requestData = array();//解析后的接收数据
	//不参与签名的键
	public $notSign = array('sign','returnType','infoType');
    /**
     * 初始化方法
     * @author zxq
     */
    protected function _initialize() {
        // 系统开关
        if (!C('TOGGLE_WEB_SITE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }
        //new环信对象
        $options['client_id'] 		= $this->client_id;
        $options['client_secret']	= $this->client_secret;
        $options['org_name']		= $this->org_name;
        $options['app_name']		= $this->app_name;
		$this->huanxin = new Easemob($options);
		$this->infoType = I('request.infoType',0);
		$this->returnType = ($this->infoType==1||$this->infoType==2) ? 'EVAL' : I('request.returnType','json');
		$requestData = file_get_contents('php://input');//接收原始数据
		$this->requestData = requestData($requestData,$this->infoType);//根据infoType解析不同数据

		if(!$this->requestData){//如果解析数据失败，使用传统方式重新接收数据
			$this->requestData = I('request.');
		}
    }
    /**
     * [errorCode 返回的错误码和对应的可能的出现的错误问题，详细错误看返回的提示]
     * @param  [type] $code [description]
     * @return [type]       [description]
     */
    public function errorInfo($code){
    	$code_arr = array(
		    		'400' =>'（错误请求）服务器不理解请求的语法',
		    		'401' =>'（（未授权）请求要求身份验证。对于需要token的接口，服务器可能返回此响应',
		    		'403' =>'（禁止）服务器拒绝请求。对于群组/聊天室服务，表示本次调用不符合群组/聊天室操作的正确逻辑，例如调用添加成员接口，添加已经在群组里的用户，或者移除聊天室中不存在的成员等操作',
		    		'404' =>'（未找到）服务器找不到请求的接口',
		    		'408' =>'（请求超时）服务器等候请求时发生超时',
		    		'413' =>'（请求体过大）请求体超过了5kb，拆成更小的请求体重试即可',
		    		'429' =>'（服务不可用）请求接口超过调用频率限制，即接口被限流',
		    		'500' =>'（服务器内部错误）服务器遇到错误，无法完成请求',
		    		'501' =>'（尚未实施）服务器不具备完成请求的功能。例如，服务器无法识别请求方法时可能会返回此代码',
		    		'502' =>'（错误网关）服务器作为网关或代理，从上游服务器收到无效响应',
		    		'503' =>'（服务不可用）请求接口超过调用频率限制，即接口被限流',
		    		'504' =>'（网关超时）服务器作为网关或代理，但是没有及时从上游服务器收到请求',
		    		'505' =>'（错误请求）服务器不理解请求的语法'
		    		);
    	if (in_array($code,$code_arr)) {
    		return $code_arr[$code];
    	}else{
    		return false;
    	}
    }
}
