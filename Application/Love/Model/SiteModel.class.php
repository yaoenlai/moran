<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Love\Model;
use Think\Model;
/**
 * 个人用户模型
 * @author zxq
 */
class SiteModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_site';
	
	/**
     * 自动映射规则
     * @author zxq
     */
    protected $_map = array(
         'sid' =>'id', // 把表单中sid映射到数据表的id字段
     );
	 
    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('title', 'require', '网站名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '', '网站名称被占用', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
        array('icon', 'require', '网站图标不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('splash', 'require', '网站启动图不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('backurl', 'url', '回调url格式错误', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    );
	
	/**
     * 自动完成规则
     * @author zxq
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );
	
	/**
     * 获取组合配置
     * @param array 原始数据
     * @return array 配置组合数组
     * @author zxq
     */
    public function toMergaList($data_list){
    	foreach ($data_list as $key => $value) {
    		//生成g_conf组合数据
    		if($this->regex($value['backurl'], 'url')){
    			$isBackUrl = '<font color="green">正常</font>';
    		}else{
    			$isBackUrl = '<font color="red">异常</font>';
    		}
			$data_list[$key]['devInfo'] = "<pre>SID:".$value['id']."\tKey:".$value['key']."\tBackURL:".$isBackUrl."\r\nSecret:".$value['secret']."</pre>";
			
    	}
		return $data_list;
    }
	
	/**
     * 获取游戏在聚合平台的配置信息
     * @author zxq
	 * @return array／false
	 * backurl(接收支付通知地址)
     */
	public function getSiteConf($sid){
		$map['id'] = $sid;
	    $conf = $this->field('id as sid,key,secret,backurl')->where($map)->find();
		if($conf){
			return $conf;
		}else{
			return FALSE;
		}
	}
	
    /*
     * 重写regex方法  原因：原系统regex方法对部分url无法匹配
     */
    public function regex($value, $rule){
        $validate = array(
            'require'  => '/\S+/',
            'email'    => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            //'url'      => '/^(https?:\/\/)?(((www\.)?[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)?\.([a-zA-Z]+))|(([0-1]?[0-9]?[0-9]|2[0-5][0-5])\.([0-1]?[0-9]?[0-9]|2[0-5][0-5])\.([0-1]?[0-9]?[0-9]|2[0-5][0-5])\.([0-1]?[0-9]?[0-9]|2[0-5][0-5]))(\:\d{0,4})?)(\/[\w- .\/?%&=]*)?$/i',
            'url'      => '/^http(s?):\/\/(((www\.)?[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+))(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}|(([0-2]?[0-9]?[0-9]|2[0-5][0-5])\.([0-2]?[0-9]?[0-9]|2[0-5][0-5])\.([0-2]?[0-9]?[0-9]|2[0-5][0-5])\.([0-2]?[0-9]?[0-9]|2[0-5][0-5])))(\:\d{0,5})?(\/[\w- .\/?%&=]*)?$/i',
            //'url'      => '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
            'currency' => '/^\d+(\.\d+)?$/',
            'number'   => '/^\d+$/',
            'zip'      => '/^\d{6}$/',
            'integer'  => '/^[-\+]?\d+$/',
            'double'   => '/^[-\+]?\d+(\.\d+)?$/',
            'english'  => '/^[A-Za-z]+$/',
        );
        // 检查是否有内置的正则表达式
        if (isset($validate[strtolower($rule)])) {
            $rule = $validate[strtolower($rule)];
        }

        return preg_match($rule, $value) === 1;
    }

    /**
     * 获取站点信息 缓存一天
     * @param $id
     * @return bool|mixed
     *
     */
    public function getSiteInfoById($id) {
        $map['id'] = $id;
        $conf = $this->field('*')->where($map)->find();
        if($conf){
            $key = md5('web_site_info_'.$id);
            S($key, $conf);
            return $conf;
        }else{
            return FALSE;
        }
    }

}
