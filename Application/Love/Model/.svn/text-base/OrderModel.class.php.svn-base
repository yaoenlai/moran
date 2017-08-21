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
 * 用户消费记录模型
 * 该类参考了OneThink的部分实现
 * @author huajie <banhuajie@163.com>
 */
class OrderModel extends Model{
    /**
     * 数据库表名
     * @author sp
     */
    protected $tableName = 'love_order';

    /**
     * 自动验证规则
     * @author sp
     */
    protected $_validate = array(

    );


    /**
     * 参数回调
     * @param $orderid
     * @param int $isAgg
     * @param string $function
     * @return array
     */
    public function sync($orderid,$isAgg=1,$function='v1') {
        // 加载配置文件
        $config = include APP_PATH.'/Love/Conf/config.php';
        $this->startTrans();
        $order_info = $this->lock(true)->where(array('orderid'=>$orderid))->find();
        if(!$order_info) {
            file_put_contents('/tmp/log/gamepay_'.date('y-m-d').'.txt',microDate("y-m-d H:i:s.x"). " 订单不存在\r\n".createLinkstring($order_info)."\r\n\r\n",FILE_APPEND);
            return returnMsg('-17','fail,订单不存在!',$order_info);
        }
        // 订单状态
        if($order_info['status'] == 1) {
            file_put_contents('/tmp/log/gamepay_'.date('y-m-d').'.txt',microDate("y-m-d H:i:s.x"). " 订单已通知\r\n".createLinkstring($order_info)."\r\n\r\n",FILE_APPEND);
            return returnMsg('-17','fail,订单已经通知过!',$order_info);
        }
        // 先充值进入 用户钱包
        $user_info = D('User/User')->detail($order_info['uid'],TRUE);

        //判断余额是否满足订单
        if($user_info['money'] < $order_info['amount']) {
            return returnMsg('-16','余额不足,请先充值',$order_info);
        }
        // 用户钱包减去

        $_dec_money = D('User/User')->where(array('id' => $order_info['uid']))->lock(true)->setDec('money', $order_info['amount']);

        $moneyStatus = $this->where('id='.$order_info['id'])->setField('money_status','1');

        //套餐信息
        $package = $config['package'];

        $own_package = $package[$order_info['package_id']];
        $vip_model = D('Love/Vip');
        $data = [
            'viplevel' => $own_package['level'],
            'note' => $own_package['alias_name'],
            'money' => $order_info['amount'],
            'status' => 1,
        ];
        $has_user = $vip_model->where(['uid' => $order_info['uid']])->find();
        if($has_user) {
            // vip 结束时间累加,暂定
            //$data['enddate'] = $has_user['enddate']+$own_package['time'];
            $data['enddate'] = time()+$own_package['time'];
            $data['update_time'] = time();
            $vip_status = $vip_model->where('uid='.$order_info['uid'])->save($data);
        } else {
            $data['uid'] = $order_info['uid'];
            $data['startdate'] = time();
            $data['enddate'] = time()+$own_package['time'];
            $data['money'] = $order_info['amount'];
            $data['dataline'] = 0;
            $data['create_time'] = time();
            $data['update_time'] = time();
            $vip_status = $vip_model->add($data);
        }
        //更新 order
        $order_data = [
            'status' => 1
        ];
        $_order_status = $this->where(['orderid'=>$orderid])->save($order_data);
        if($_dec_money && $_order_status && $vip_status) {
            $this->commit();
            return returnMsg(1,'success');
        } else {
            $this->rollback();
            return returnMsg(-17,'fail');
        }

    }

}
