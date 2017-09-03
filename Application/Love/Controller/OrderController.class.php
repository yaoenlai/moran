<?php

// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

namespace Love\Controller;

use Love\Controller\CommonController;
use Addons\Pay\ThinkPay\Pay;

class OrderController extends CommonController {

    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 支付首页
     */
    public function index() {
        $token = I('access_token');
        if (!empty($token)) {
            $userInfo = $this->parse_access_token($token);
            $uid = $userInfo[1];
        } else {
            $uid = is_login();
        }
        if (!$uid) {
            echo '客户端尚未登录';
            exit;
        }
        $package = C('package');
        // get user information
        $user_info = D('Love/User')->where(['id' => $uid])->find();
        if (!$user_info) {
            echo '非法用户';
            exit;
        }
        //dump(C());exit;
        $this->assign('meta_title', '在线充值');
        $this->assign('package', $package);
        $this->assign('user', $user_info);
        $this->display();
    }

    /**
     * 支付方式
     */
    public function detail() {
        $package = C('package');
        $user_id = I('post.user_id');
        $package = C('package');
        // get user information
        $user_info = D('Love/User')->where(['id' => $user_id])->find();
        if (!$user_info) {
            echo '非法用户';
            exit;
        }
        $payway = I('post.payway');
        $has_package = isset($package[$payway]) ? true : false;
        if (!$has_package) {
            $this->error('套餐不存在');
        }
        $show_phone_input = $this->showPhoneInput($package, $payway);
        $this->assign('meta_title', '套餐详情');
        $this->assign('user', $user_info);
        $this->assign('package_id', $payway);
        $this->assign('choice_package', $package[$payway]);
        $this->assign('show_phone_input', $show_phone_input);
        $this->display();
    }

    /**
     * @param $package
     * @param $payway
     * @return bool
     */
    public function showPhoneInput($package, $payway) {
        $data = [];
        foreach ($package as $k => $v) {
            if (!empty($v['gift'])) {
                $data[] = $k;
            }
        }
        return in_array($payway, $data);
    }

    /**
     * 订单提交
     */
    public function pay() {
        $user_id = I('post.user_id');
        $package_id = I('post.package_id');
        // pay method === 1: 苹果支付 2:支付宝 3:微信支付
        $channel_type = I('post.channel_type');
        $paymethod = I('post.paymethod');
        $package = C('package');
        $has_package = isset($package[$package_id]) ? true : false;

        if (!$has_package) {
            $this->error('套餐不存在');
        }
        // 用户选择的套餐信息
        $choice_package = $package[$package_id];

        $pay_way = 'alipay';
        switch ($paymethod) {
            case 1:
                $pay_way = 'alipay';
                $pay_name = 'nowpay';
                break;
            case 2:
                $pay_way = 'weixin';
                $pay_name = 'bbnpay';
                break;
            default:
                $pay_name = 'alipay';
        }

        //构建充值订单数据
        $pay_config = D('Addons://Pay/Pay')->pay_config($pay_name);
        $rechargeApiDomain = C('recharge_config.domain_api');
        $pay_config['notify_url'] = $rechargeApiDomain . U("Recharge/Index/notify", array('apitype' => $pay_name, 'method' => 'notify', 'gid' => $this->gid), 'api');
        $pay_config['return_url'] = $rechargeApiDomain . U("Recharge/Index/notify", array('apitype' => $pay_name, 'method' => 'return'), false);
        // 订单数据
        $pay = new Pay($pay_name, $pay_config);
        $money = $choice_package['money'];
        //组建支付数组pay_data
        $pay_data['out_trade_no'] = Pay::createOrderNo(D('Admin/Module')->getModuleID(), 1); //充值余额订单
        $pay_data['money'] = $money * 100; //单位分
        $pay_data['pay_type'] = $pay_name;                                              //这是前台用户选择的支付方式，比如用户选择了微信，那么这个值就是wxpay
        $pay_data['title'] = $choice_package['alias_name'];
        $pay_data['body'] = $choice_package['alias_name'];
        $pay_data['goodsid'] = $choice_package['payid'];
        // 微信 支付宝
        // 现代支付（微信、支付宝）
        if (!empty($channel_type)) {
            $pay_data['channel_type'] = $pay_way;
        }

        $order_data['uid'] = $user_id;
        $order_data['note'] = json_encode(array($pay_data['out_trade_no'])); //用于回调方法callfunc的参数
        $order_data['callfunc'] = 'Love/Order-sync'; //模块/控制器-方法|方法必须以$pay_data['note']为参数
        $order_data = array_merge($pay_data, $order_data); //合并出订单数组$order_data
        // create order
        $order_model = D("Love/Order");
        $order_model->uid = $user_id;
        $order_model->productname = $choice_package['alias_name'];
        $order_model->productdesc = $choice_package['alias_name'];
        $order_model->orderid = $pay_data['out_trade_no'];
        $order_model->package_id = $package_id;
        $order_model->amount = $money * 100;
        $order_model->status = 0;
        $order_model->money = $money * 100;
        $order_model->pay_type = $pay_name;
        $order_model->channel_type = $pay_way;
        $order_model->ip = get_client_ip();
        $order_model->create_time = time();
        $user_profile = D('Love/Profile')->find($user_id);
        $order_model->sid = !empty($user_profile['sid']) ? $user_profile['sid'] : 1; //如果不存在就当作主站订单
        $r_order = $order_model->add();
        if (!$r_order) {
            $this->error('创建订单失败');
        }
        //创建订单
        $recharge_object = D('Recharge/Index');
        $data = $recharge_object->create($order_data);
        $add_result = $recharge_object->add($data);

        if ($add_result) {
            $pay_data['money'] = moneyFormat($pay_data['money']); //再将数据转化为元
            $result = $pay->buildRequestForm($pay_data);
            if (is_array($result)) {
                file_put_contents('/tmp/log/gamepay_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . ' ' . json_encode($result), FILE_APPEND);
            } else {
                echo $result;
            }
        }
    }

    /**
     *  充值规则
     */
    public function rule() {
        $this->assign('meta_title', '充值规则');
        $this->display();
    }

    /**
     * 支付完成页面
     */
    public function completed() {
        $this->assign('meta_title', '支付完成');
        $this->display();
    }

}
