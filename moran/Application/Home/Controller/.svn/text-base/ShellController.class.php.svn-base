<?php
// +----------------------------------------------------------------------
// | GamePlat [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.yongshihuyu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: yyl
// +----------------------------------------------------------------------
// | @email 944677073@qq.com
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Common\Util\Easemob;//加载系统控制器
/**
 * shell脚本父控制器
 * @author yyl
 * @email 944677073@qq.com
 */
class ShellController extends Controller {

     /**
     * 环信参数配置
     * @author yyl
     * @email 944677073@qq.com
     */
    private   $client_id        =   'YXA6f83TAP1dEeaBdXszhlCfmQ';
    private   $client_secret    =   'YXA63-G5bdQ_qTUHD7rNUEqQ4PoOFds';
    private   $org_name         =   '1113170228178682';
    private   $app_name         =   'myapp001';
    public    $huanxin      ;//环信对象
    //

    /**
     * 初始化方法
     */
    protected function _initialize() {
        // 系统开关
        if (!C('TOGGLE_WEB_SITE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }
        //new环信对象
        $options['client_id']       = $this->client_id;
        $options['client_secret']   = $this->client_secret;
        $options['org_name']        = $this->org_name;
        $options['app_name']        = $this->app_name;
        $this->huanxin = new Easemob($options);
    }
    public function index(){
		echo "这是shell入口\r\n";
    }

}