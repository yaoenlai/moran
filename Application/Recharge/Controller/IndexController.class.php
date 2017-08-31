<?php

// +----------------------------------------------------------------------
// | GamePlat [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.yongshihuyu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

namespace Recharge\Controller;

use Home\Controller\HomeController;
use Common\Util\Think\Page;
use Addons\Pay\ThinkPay\Pay;

/**
 * 默认控制器
 * @author zxq
 */
class IndexController extends HomeController {

    /**
     * 充值首页
     * @author zxq
     */
    public function index() {
        $addon_pay_config = json_decode(D('Admin/Addon')->getFieldByName('Pay', 'config'), true);
        if (IS_POST) {//C('IS_API', true);
            $this->is_login();
            $pay_type = I('post.pay_type');
            if (\Common\Util\Device::isWap() && $pay_type === 'alipay' && !C('IS_API')) {
                $pay_type = 'aliwappay';
            }
            // 订单数据
            $pay_config = D('Addons://Pay/Pay')->pay_config($pay_type);
            $pay_config['notify_url'] = U("notify", array('apitype' => $pay_type, 'method' => 'notify'), false, true);
            $pay_config['return_url'] = U("notify", array('apitype' => $pay_type, 'method' => 'return'), false, true);

            // 订单数据
            $pay = new Pay($pay_type, $pay_config);
            // $str = '{"discount":"0.00","payment_type":"1","subject":"\u52c7\u58eb\u4e92\u5a31\u4f59\u989d\u5145\u503c","trade_no":"2016091821001004200254705722","buyer_email":"18610773198","gmt_create":"2016-09-18 19:07:45","notify_type":"trade_status_sync","quantity":"1","out_trade_no":"160918060011907215710057","seller_id":"2088221532419380","notify_time":"2016-09-18 19:08:01","body":"\u52c7\u58eb\u4e92\u5a31\u4f59\u989d\u5145\u503c","trade_status":"TRADE_SUCCESS","is_total_fee_adjust":"N","total_fee":"0.01","gmt_payment":"2016-09-18 19:08:00","seller_email":"fangcheng@yongshihuyu.com","price":"0.01","buyer_id":"2088802760158207","notify_id":"01c2648bf910ac11a2db47672b69e32hjm","use_coupon":"N","sign_type":"MD5","sign":"e01ec378b1472b36e177fa21e9aac31b"}';

            $pay_data['out_trade_no'] = $pay->createOrderNo();
            $pay_data['title'] = C('WEB_SITE_TITLE') . "余额充值";
            $pay_data['body'] = C('WEB_SITE_TITLE') . "余额充值";
            $pay_data['money'] = moneyFormat(I('post.money'), 2); //sprintf("%0.2f", I('post.money'));
            $pay_data['pay_type'] = $pay_type;
            $pay_data['channel_type'] = 'alipay'; //QQ  weixinH5
            //创建订单
            $recharge_object = D('Index');
            $order_data['uid'] = $this->is_login();
            $order_data = array_merge($pay_data, $order_data); //合并出订单数组$order_data
            $data = $recharge_object->create($order_data);
            $add_result = $recharge_object->add($data);
            // 构建支付平台表单
            if ($add_result) {
                $pay_data['money'] = moneyFormat($pay_data['money']); //再将数据转化为元
                $result = $pay->buildRequestForm($pay_data);
                if (is_array($result)) {
                    $this->ajaxReturn($result);
                } else {
                    echo $result;
                }
            }
        } else {//呈现支付选择页面
            Cookie('__forward__', $_SERVER['REQUEST_URI']);
            /*
              foreach ($addon_pay_config['allow_pay_type'] as $key => $value){
              if (stripos($value, 'wap')) {
              unset($addon_pay_config['allow_pay_type'][$key]);
              $addon_pay_config['allow_wappay_type'][$key] = $value;
              }
              }
              if(is_wap()){
              $allow_pay_type = $addon_pay_config['allow_wappay_type'];
              }else{
              $allow_pay_type = $addon_pay_config['allow_pay_type'];
              }
             */
            $userinfo = D('User/User')->detail(is_login());
            //$userinfo['money'] = moneyFormat($userinfo['money']);//将用户余额转化为元
            $allow_pay_type = $addon_pay_config['allow_pay_type'];
            $this->assign('allow_pay_type', $allow_pay_type);
            $this->assign('meta_title', '充值');
            $this->assign('userinfo', $userinfo); //用户信息
            $this->display();
        }
    }

    /**
     * 支付同步回调
     * @param array $notify
     */
    public function returnnotify() {
        $notify = isset($notify) ? $notify : I('request.');
        $method = isset($notify['method']) ? $notify['method'] : I('request.method');
        $apitype = isset($notify['apitype']) ? $notify['apitype'] : I('request.apitype');
        if (empty($notify)) {
            parse_str(file_get_contents('php://input'), $notify);
        }
        // 特殊处理 bbnpay 默认让他成功
        if ($apitype == 'bbnpay' && $method == 'return') {
            redirect('/Love/order/completed', 0);
        }
    }

    /**
     * 支付结果返回
     */
    public function notify($notify = array()) {
        //处理并格式化参数
        $notify = isset($notify) ? $notify : I('request.');
        //保留本地调试，安全测试后删除
        /*
          $json_data = '{"transdata":"{\"transtype\":0,\"cporderid\":\"170507030010815344910248\",\"transid\":\"0000931494116134308744914738\",\"pcuserid\":\"14\",\"appid\":\"1902017050593398\",\"goodsid\":\"127\",\"feetype\":4,\"money\":10,\"fact_money\":10,\"currency\":\"CHY\",\"result\":1,\"transtime\":\"20170507081541\",\"pc_priv_info\":\"\",\"paytype\":\"1\"}","sign":"163c7ab7dd015bf315c5a1526c996a98","signtype":"MD5"}';
          $notify = json_decode($json_data, true);
         */
        //注销不用的参数
        $method = isset($notify['method']) ? $notify['method'] : I('request.method');
        $apitype = isset($notify['apitype']) ? $notify['apitype'] : I('request.apitype');
        unset($notify['method']);
        unset($notify['apitype']);
        if (empty($notify)) {
            parse_str(file_get_contents('php://input'), $notify);
        }
        //dump($notify);exit;
        //实例化Pay插件
        $pay_config = D('Addons://Pay/Pay')->pay_config($apitype);
        $pay = new Pay($apitype, $pay_config);
        //exit;
        file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n开始通知\r\n" . microDate("y-m-d H:i:s.x") . ' ' . json_encode($notify), FILE_APPEND);
        if ($method == "return") {//跳转个人中心
            $this->redirect("User/Center/index");
        }

        // 验证参数真伪性
        if ($pay->verifyNotify($notify)) {
            //判定订单，开启事务,锁定订单
            $pay_object = D('Index');
            $pay_object->startTrans(); //开启事务
            $pay_info = $pay->getInfo(); //获取订单信息
            $con['out_trade_no'] = $pay_info['out_trade_no'];
            $tradeInfo = $pay_object->lock(true)->where($con)/* ->fetchSql(true) */->find();
            if ($tradeInfo) {
                if ($tradeInfo['money'] != $pay_info['money']) {//判断通知金额和聚合订单金额是否相同
                    $pay_object->rollback(); //回滚
                    file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . " fail,通知金额与订单金额不同{$tradeInfo['money']}-{$pay_info['money']}", FILE_APPEND);
                    exit("fail,通知金额与订单金额不同");
                }
                if ($tradeInfo['is_callback'] == 1) {//防止重复订单
                    $pay_object->rollback(); //回滚
                    file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . ' ,已经通知过' . json_encode($tradeInfo), FILE_APPEND);
                    exit($pay->notifySuccess());
                }
                if ($tradeInfo['status'] != 1) {//证明验证通过且进行了回调
                    $pay_status['status'] = 1;
                    $status = $pay_object->where($con)->setField($pay_status);
                }
                if ($pay_info['status'] === true) {//如果订单支付成功
                    // 设置支付成功标记
                    if ($tradeInfo['is_pay'] != 1) {
                        $pay_success['is_pay'] = 1;
                        $is_pay = $pay_object->where($con)->setField($pay_success);
                    }

                    // 执行回调函数完成比如充值后的数据操作
                    $callback_status = $this->rechargeCallback($tradeInfo);
                    if ($callback_status === true) {
                        // 设置回调成功标记
                        $callback_success['is_callback'] = 1;
                        $is_callback = $pay_object->where($con)->setField($callback_success);
                        if ($is_callback) {//回调成功,处理发起请求的模块预置回调函数(消费函数)
                            if (!empty($tradeInfo['callfunc'])) {
                                $funcArr = explode('-', $tradeInfo['callfunc']); //Game/Index-sync
                                $isMethod = method_exists(D($funcArr[0]), $funcArr[1]);
                                if ($isMethod) {
                                    $params = json_decode($tradeInfo['note'], true);
                                    $modulePay = D($funcArr[0])->$funcArr[1]($params[0], $params[1], $params[2]);
                                    dump($modulePay);
                                    if ($modulePay['code'] != 1 && $modulePay['code'] != '-17') {
                                        file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . ' fail,道具发放失败', FILE_APPEND);
                                        exit('fail,道具发放失败');
                                    }
                                } else {
                                    file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . ' fail,回调方法不存在', FILE_APPEND);
                                    exit('fail,回调方法不存在');
                                }
                            }
                            file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . ' success,道具发放成功', FILE_APPEND);
                            $pay_object->commit(); //事务提交
                            $pay->notifySuccess();
                            exit;
                        } else {//记录错误日志
                            file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . ' fail,更改余额订单为成功时失败', FILE_APPEND);
                        }
                    }
                } else {
                    file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . ' fail,订单未支付成功', FILE_APPEND);
                    //$pay->notifySuccess();exit;
                    //exit("fail,订单未支付成功!");
                }
                $pay_object->commit(); //事务提交
                $pay->notifySuccess();
                exit;
            } else {
                $pay_object->rollback(); //回滚
                file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . ' fail,订单不存在!' . json_encode($pay_info), FILE_APPEND);
                exit("fail,订单不存在!");
            }
        } else {
            //验签失败
            file_put_contents('/tmp/gamenotify_' . date('y-m-d') . '.txt', "\r\n\r\n" . microDate("y-m-d H:i:s.x") . " 验签失败", FILE_APPEND);
            // 情况比较特殊，由于 bbnpay 支付方式，只有notify 通知，且多次回调接口，暂时将他跳转至 支付成功页面，操蛋的做法
            // 猜想原因: 可能是某订单一尚未处理完成，且本次回调一起发送过来导致本次回调失败 其实是上一次的
            if ($apitype == 'bbnpay') {
                redirect('/Love/order/completed', 0);
            } else {
                exit('fail,验签失败');
            }
        }
    }

    /**
     * 余额充值成功回调接口
     * @param array $tradeInfo 订单信息，必须包含$money $uid
     * @return boolean
     */
    private function rechargeCallback($tradeInfo) {
        if (!$tradeInfo['uid'] || !$tradeInfo['money']) {
            return false;
        }
        $uid = $tradeInfo['uid'];
        $money = $tradeInfo['money'];
        $user_object = D('User/User');
        $user_object->startTrans(); //开启事务
        $result = $user_object->where(array('id' => $uid))
                        ->lock(true)->setInc('money', $money); //用户Money+$money
        $user_object->commit(); //提交事务
        $money = moneyFormat($money); //将金钱单位转化为元
        if ($result != false) {
            $msg_data['title'] = '充值成功，' . $money . '元已到账！'; // 消息标题
            $msg_data['content'] = '充值成功，' . $money . '元已到账！'; // 消息内容
            $msg_data['to_uid'] = $uid; //消息收信人ID
            D('User/Message')->sendMessage($msg_data);
            return true;
        } else {
            E($user_object->getError());
            return false;
        }
    }

}
