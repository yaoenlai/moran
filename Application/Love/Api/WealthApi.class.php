<?php
// +----------------------------------------------------------------------
// | GamePlat [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.yongshihuyu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Game\Api;
use Game\Api\Api;
use Addons\Pay\ThinkPay\Pay;
/**
 * 财富相关接口
 * @author zxq
 */
class WealthApi extends Api {

	/**
     * 支付接口(游戏内道具购买接口)
	 * 自有渠道sdk:http://www.isgcn.com/api/game/Wealth/payMent.api  True
	 * 自有聚合sdk:http://www.isgcn.com/api/game/Wealth/payMent/isagg/1.api false
     * @author zxq
     */
	public function payMent(){
		if($this->verifySign()){
			//检测设备
	        $deviceResult = $this->checkDevice();
			if($deviceResult['code'] != 1){
				$this->ajaxReturn(returnInfo($deviceResult['code'],$deviceResult['msg'],null,$this->infoType),$this->returnType);
			}else{
				$deviceInfo = $deviceResult['data'];
			}
			//$paySync = D('Game/Pay')->sync('160926040012030280010298');
			//dump($paySync);exit;
			$post = $this->requestData;
			$post['orderid'] = Pay::createOrderNo(D('Admin/Module')->getModuleID(),1);//消费订单号
			$post['ip'] = sprintf("%u", ip2long($post['ip']));
			$post['uid'] = $post['userID'];

			$userInfo = D('User/User')->detail($post['uid']);
			if($userInfo['money'] < $post['money'])//判断余额是否大于提交余额
				$this->ajaxReturn(returnInfo('-21','用户余额不足!',null,$this->infoType),$this->returnType);
			$yxb = D('Game/Yxb')->getYxb($post['uid'],$post['gid']);
			if($yxb < $post['yxb'])//判断余额是否大于提交余额
				$this->ajaxReturn(returnInfo('-22','用户游戏币不足!',null,$this->infoType),$this->returnType);

			$post['register_aid'] = !empty($userInfo['aid']) ? $userInfo['aid'] : $post['aid'];

			$agentInfo = D('User/Agent')->getAgentInfo($post['aid']);//获取当前支付渠道的详细信息
			$post['paid'] = !empty($agentInfo['pid']) ? $agentInfo['pid'] : 0;

			$object = D('Game/Pay');
			$data = $object->create($post);//构建消费订单
			if($data){
				$res = $object->add();
				$data['id'] = $res;
			} else {
                $this->ajaxReturn(returnInfo('-5',$object->getError(),null),$this->returnType);
            }

			$isAgg = !empty($post['isagg']) ? $post['isagg'] : I('request.isagg',0);//1 自有聚合 其他数字 自有渠道
			$apiVer = !empty($post['ver']) ? $post['ver'] :I('request.ver','v1');//如果是聚合 那么使用聚合的接口版本 默认v1
			file_put_contents('/tmp/log/gamepay_'.date('y-m-d').'.txt', "\r\n\r\n".microDate("y-m-d H:i:s.x").' '.json_encode($post),FILE_APPEND);
			//判断还需第三方支付金额，如果0，直接回调并返回
			$money = $post['amount']-($post['money']+$post['yxb']);//还需支付的金额 如果还需要支付大于0 那么走第三方，如果等于0 直接回调走余额
			if($money == 0 || $post['pay_type'] == 'money'){//如果还需支付金额为0
				if(0 == $money){
					$paySync = D('Game/Pay')->sync($post['orderid'],$isAgg,$apiVer);
					$this->ajaxReturn(returnInfo($paySync['code'],$paySync['msg'],$paySync['data'],$this->infoType),$this->returnType);
				}else{
					$this->ajaxReturn(returnInfo('-30',  '订单金额有误!',null,$this->infoType),$this->returnType);
				}
				exit;
			}
			/*-----------------如果还需要第三方支付那么继续下面逻辑-----------------------*/
			//构建充值订单数据
			$pay_type = $post['pay_type'];  // 支付方式alipay wxpay等等
			$pay_config = D('Addons://Pay/Pay')->pay_config($pay_type);
            $pay_config['notify_url'] = U("Recharge/Index/notify", array('apitype' => $pay_type, 'method' => 'notify','gid' => $this->gid), false, true);
            $pay_config['return_url'] = U("Recharge/Index/notify", array('apitype' => $pay_type, 'method' => 'return'), false, true);
            // 订单数据
            $pay = new Pay($pay_type, $pay_config);

			//组建支付数组pay_data
		    $pay_data['out_trade_no'] = Pay::createOrderNo(D('Admin/Module')->getModuleID(),1);//充值余额订单
		    $pay_data['money']        = $money;//单位分
		    $pay_data['pay_type']     = $pay_type;//这是前台用户选择的支付方式，比如用户选择了微信，那么这个值就是wxpay
		    $pay_data['title'] = $post['productname'];
		    $pay_data['body']  = (strlen($post['productdesc'])>2) ? $post['productdesc'] : $post['productdesc'].'_'.$post['userID'];
			if(!empty($post['channel_type']))
				$pay_data['channel_type'] = $post['channel_type'];//'weixin';//QQ
			$order_data['uid'] = $post['userID'];
			$order_data['note']  = json_encode(array($post['orderid'],$isAgg,$apiVer));//用于回调方法callfunc的参数
			$order_data['callfunc']  = 'Game/Pay-sync';//模块/控制器-方法|方法必须以$pay_data['note']为参数
	    	//$funcArr = explode('-', $order_data['callfunc']);//Game/Pay-sync
			//$isMethod = method_exists(D($funcArr[0]),$funcArr[1]);dump($isMethod);exit;
			$order_data = array_merge($pay_data,$order_data);//合并出订单数组$order_data
			//创建订单
			$recharge_object = D('Recharge/Index');
	        $data = $recharge_object->create($order_data);
	        $add_result = $recharge_object->add($data);
			if($add_result){
            	$pay_data['money'] = moneyFormat($pay_data['money']);//再将数据转化为元
            	$result = $pay->buildRequestForm($pay_data);
				if(is_array($result)){
					file_put_contents('/tmp/log/gamepay_'.date('y-m-d').'.txt', "\r\n\r\n".microDate("y-m-d H:i:s.x").' '.json_encode($result),FILE_APPEND);
					$this->ajaxReturn(returnInfo('1','请调起支付!',$result,$this->infoType),$this->returnType);
					//$this->ajaxReturn($result);
				}else{
					echo $result;
				}
            }
		}else{
			$this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
		}
		exit;
	}

	/**
     * 支付接口(游戏内道具购买接口)
	 * 自有渠道sdk:http://www.isgcn.com/api/game/Wealth/paySync.api  True
	 * 自有聚合sdk:http://www.isgcn.com/api/game/Wealth/paySync/isagg/1.api false
     * @author zxq
     */
	public function paySync(){
		$post = $this->requestData;
		$key = C('game_config.CONFIG_SECRET_STRING');
		$iSign = md5($post['orderid'].'5e54f942289a82685dd075352821ea71'.$key);
		if($iSign == $post['sign']){
			$paySync = D('Game/Pay')->sync($post['orderid']);
			$this->ajaxReturn(returnInfo($paySync['code'],$paySync['msg'],$paySync['data'],$this->infoType),$this->returnType);
		}else{
			$this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
		}
	}

	/**
     * 用户余额接口(游戏币和平台币获取接口)
	 * http://www.isgcn.com/api/aggregation/Wealth/uCenter.api
     * @author zxq
     */
    public function uCenter(){
        if($this->verifySign()){
            $deviceResult = $this->checkDevice();
            if($deviceResult['code'] != 1){
                $this->ajaxReturn(returnInfo($deviceResult['code'],$deviceResult['msg'],null,$this->infoType),$this->returnType);
            }else{
                $deviceInfo = $deviceResult['data'];
            }
            $post = $this->requestData;
            $userInfo = D('User/User')->detail($post['userID']);
            $yxb_num = D('Game/Yxb')->getYxb($post['userID'],$post['gid']);
            if($userInfo){
                $data = array(
                    'userID' => $userInfo['id'],
                    'gid' => $post['gid'],
                    'email' => $userInfo['email'],
                    'yxb_num' => $yxb_num,
                    'money' => $userInfo['money'],
                );
                $this->ajaxReturn(returnInfo('1','获取成功!',$data,$this->infoType),$this->returnType);
            }else{
                $this->ajaxReturn(returnInfo('-5','查询用户信息不存在!',null,$this->infoType),$this->returnType);
            }
        }else{
            $this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
        }
        exit;
    }

	/**
     * 支付接口(游戏内道具购买接口)
	 * http://www.isgcn.com/api/game/Wealth/recharge.api
     * @author zxq
     */
	public function recharge(){
		if($this->verifySign()){
			//检测设备
	        $deviceResult = $this->checkDevice();
			if($deviceResult['code'] != 1){
				$this->ajaxReturn(returnInfo($deviceResult['code'],$deviceResult['msg'],null,$this->infoType),$this->returnType);
			}else{
				$deviceInfo = $deviceResult['data'];
			}
			$post = $this->requestData;
			//file_put_contents('/tmp/log/recharge.txt',microDate("y-m-d H:i:s.x").' '. "开启充值余额\r\n".createLinkstring($post)."\r\n",FILE_APPEND);
			//构建充值订单数据
			$pay_type = $post['pay_type'];  // 支付方式alipay wxpay等等
			$pay_config = D('Addons://Pay/Pay')->pay_config($pay_type);
            $pay_config['notify_url'] = U("Recharge/Index/notify", array('apitype' => $pay_type, 'method' => 'notify'), false, true);
            $pay_config['return_url'] = U("Recharge/Index/notify", array('apitype' => $pay_type, 'method' => 'return'), false, true);
            // 订单数据
            $pay = new Pay($pay_type, $pay_config);

			//组建支付数组pay_data
		    $pay_data['out_trade_no'] = Pay::createOrderNo(D('Admin/Module')->getModuleID(),1);//充值余额订单
		    $pay_data['money']        = $post['money'];//单位分
		    $pay_data['pay_type']     = $pay_type;//这是前台用户选择的支付方式，比如用户选择了微信，那么这个值就是wxpay
		    $pay_data['title'] = $post['productname'];
		    $pay_data['body']  = $post['productdesc'];
			if(!empty($post['channel_type']))
				$pay_data['channel_type'] = $post['channel_type'];//'weixin';//QQ

			$order_data['uid'] = $post['userID'];
			$order_data = array_merge($pay_data,$order_data);//合并出订单数组$order_data
			//创建订单
			$recharge_object = D('Recharge/Index');
	        $data = $recharge_object->create($order_data);
	        $add_result = $recharge_object->add($data);
			if($add_result){
            	$pay_data['money'] = moneyFormat($pay_data['money']);//再将数据转化为元
            	$result = $pay->buildRequestForm($pay_data);
				if(is_array($result)){
					$this->ajaxReturn(returnInfo('1','请调起支付!',$result,$this->infoType),$this->returnType);
					//$this->ajaxReturn($result);
				}else{
					echo $result;
				}
            }
		}else{
			$this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
		}
		exit;
	}

	/*
	 * 以及下列接口都放在该文件里面
	 *
	 * 用户信息修改(待定接口)
  传参:gid userID aid ＋表单内容

	 */
    /**余额充值记录
     * http://www.isgcn.com/api/game/Wealth/rechargeList.api
     * */
    public function rechargeList(){
        if($this->verifySign()){
            $deviceResult = $this->checkDevice();
            if($deviceResult['code'] != 1){
                $this->ajaxReturn(returnInfo($deviceResult['code'],$deviceResult['msg'],null,$this->infoType),$this->returnType);
            }else{
                $deviceInfo = $deviceResult['data'];
            }

            $post = $this->requestData;
            if(empty($post['userID']))
                $this->ajaxReturn(returnInfo('-23','用户id不能为空!',null,$this->infoType),$this->returnType);

            $where['uid'] = $post['userID'];
            $recharge = D('Recharge/Index')->getRechargeList($where,$post['page'],$post['offset']);
            if(!empty($recharge)){
/*                foreach($recharge['data'] as $key => $val){
                    $recharge['data'][$key]['money'] = moneyFormat($val['money'],1);
                }*/
                $data = array('total'=>$recharge['total'],'rows'=>$recharge['data'],'type'=>'recharge');
                $this->ajaxReturn(returnInfo('1','获取成功!',$data,$this->infoType),$this->returnType);
            }else{
                $this->ajaxReturn(returnInfo('-7','余额充值记录!',null,$this->infoType),$this->returnType);
            }
        }else{
            $this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
        }
        exit;
    }

    /**余额充值订单详情
     * http://www.isgcn.com/api/game/Wealth/rechargeDetail.api
     * */
    public function rechargeDetail(){
        if($this->verifySign()){
            $post = $this->requestData;
            if(empty($post['orderId']))
                $this->ajaxReturn(returnInfo('-24','订单编号不能为空!',null,$this->infoType),$this->returnType);

            $where['out_trade_no'] = $post['orderId'];
            $data = D('Recharge/Index')->where($where)->find();
            if(!empty($data)){
                //$data['money'] = moneyFormat($data['money'],1);
                $this->ajaxReturn(returnInfo('1','获取成功!',$data,$this->infoType),$this->returnType);
            }else{
                $this->ajaxReturn(returnInfo('-7','余额充值详情不存在!',null,$this->infoType),$this->returnType);
            }
        }else{
            $this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
        }
        exit;
    }


    /**游戏币获取记录
     * http://www.isgcn.com/api/game/Wealth/yxb.api
     * */
    public function yxb(){
        if($this->verifySign()){
            $deviceResult = $this->checkDevice();
            if($deviceResult['code'] != 1){
                $this->ajaxReturn(returnInfo($deviceResult['code'],$deviceResult['msg'],null,$this->infoType),$this->returnType);
            }else{
                $deviceInfo = $deviceResult['data'];
            }

            $post = $this->requestData;
            $page = isset($post['page']) ? $post['page'] : 1;
            $offset = isset($post['offset']) ? $post['offset'] : 5;
            if(empty($post['userID']))
                $this->ajaxReturn(returnInfo('-23','用户id不能为空!',null,$this->infoType),$this->returnType);

            $where['uid'] = $post['userID'];
            $count = M('game_yxbhistory')->where($where)->order('create_time desc')->count();
            $total = ceil($count/$offset);//总页数  = 总条数/条数
            $page = ($page - 1) * $offset;//从$page开始
            $yxb = M('game_yxbhistory')->where($where)->order('create_time desc')->limit($page,$offset)->select();
            $gameList = D('Game/Index')->gameList();
            if(!empty($yxb)){
                foreach($yxb as $key=>$val){
                    $yxb[$key]['game'] = $gameList[$val['gid']];
                }
                $data = array('total'=>$total,'rows'=>$yxb,'type'=>'yxb');
                $this->ajaxReturn(returnInfo('1','获取成功!',$data,$this->infoType),$this->returnType);
            }else{
                $this->ajaxReturn(returnInfo('-7','查询的游戏币记录为空!',null,$this->infoType),$this->returnType);
            }
        }else{
            $this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
        }
        exit;
    }

    /**游戏币获取详情
     * http://www.isgcn.com/api/game/Wealth/yxbDetail.api
     * */
    public function yxbDetail(){
        if($this->verifySign()){
            $post = $this->requestData;
            if(empty($post['id']))
                $this->ajaxReturn(returnInfo('-24','id不能为空!',null,$this->infoType),$this->returnType);

            $yxb = D('game_yxbhistory')->find($post['id']);
            $data = array();
            $gameList = D('Game/Index')->gameList();
            if(!empty($yxb)){
                $yxb['game'] = $gameList[$yxb['gid']];
                $this->ajaxReturn(returnInfo('1','获取成功!',$yxb,$this->infoType),$this->returnType);

            }else{
                $this->ajaxReturn(returnInfo('-7','查询的该详情不存在!',$data,$this->infoType),$this->returnType);

            }
        }else{
            $this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
        }
        exit;
    }

    /**
     *消费订单
     * http://www.isgcn.com/api/game/Wealth/payList.api
     */

    public function payList(){
        if($this->verifySign()){
            $post = $this->requestData;
            $deviceResult = $this->checkDevice();
            if($deviceResult['code'] != 1){
                $this->ajaxReturn(returnInfo($deviceResult['code'],$deviceResult['msg'],null,$this->infoType),$this->returnType);
            }else{
                $deviceInfo = $deviceResult['data'];
            }

            if(empty($post['userID']))
                $this->ajaxReturn(returnInfo('-23','用户id不能为空!',null,$this->infoType),$this->returnType);

            //判断状态是否存在
            if(!empty($post['status']))
                $where['status'] = $post['status'];

            $where['uid'] = $post['userID'];
            $pay = D('Game/Pay')->getPayList($where,$post['page'],$post['offset']);
            /*            $count = D('Game/Pay')->where($where)->count();
                        $total = ceil($count/$offset);//总页数  = 总条数/条数
                        $page = ($page - 1) * $offset;//从$page开始
                        $pay = D('Game/Pay')->field('id,uid,productname,amount,productdesc,status,create_time')->where($where)->order('create_time desc')->limit($page,$offset)->select();*/
            $gameList = D('Game/Index')->gameList();
            $data = array('total'=>0,'rows'=>array(),'type'=>'pay');
            if(!empty($pay)){
                foreach($pay['data'] as $key=>$val){
                    $pay['data'][$key]['out_trade_no'] = $val['orderid'];
                    $pay['data'][$key]['game'] = $gameList[$val['gid']];
                    unset($pay['data'][$key]['orderid']);
                }
                $data = array('total'=>intval($pay['total']),'rows'=>$pay['data'],'type'=>'pay');
                $this->ajaxReturn(returnInfo('1','获取成功!',$data,$this->infoType),$this->returnType);
            }else{
                $this->ajaxReturn(returnInfo('-7','查询的消费订单记录为空!',$data,$this->infoType),$this->returnType);
            }
        }else{
            $this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
        }
        exit;
    }

    /**
     *消费订单详情
     * http://www.isgcn.com/api/game/Wealth/payDetail.api
     * @param array
     */
    public function payDetail(){
        if($this->verifySign()){
            $post = $this->requestData;
            if(empty($post['id']))
                $this->ajaxReturn(returnInfo('-24','id不能为空!',null,$this->infoType),$this->returnType);

            $status = D('Game/Pay')->getStatus();
            $gameList = D('Game/Index')->gameList();
            $pay = D('Game/Pay')->find($post['id']);
            if(!empty($pay)){
                $pay['out_trade_no'] = $pay['orderid'];
                unset($pay['orderid']);
                $this->ajaxReturn(returnInfo('1','获取成功!',$pay,$this->infoType),$this->returnType);
            }else{
                $this->ajaxReturn(returnInfo('-7','查询的消费订单详情不存在!',null,$this->infoType),$this->returnType);
            }
        }else{
            $this->ajaxReturn(returnInfo('-3','验签失败!',null,$this->infoType),$this->returnType);
        }
        exit;
    }

}