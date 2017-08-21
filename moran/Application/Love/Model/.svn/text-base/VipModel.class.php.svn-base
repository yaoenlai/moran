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
 * 用户模型
 * @author zxq
 */
class VipModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_vip';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('uid', 'require', '用户ID必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('uid', '', '1', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
        array('viplevel', 'require', '用户等级必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('startdate', 'require', '开始时间', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('enddate', 'require', '结束时间', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    );

    /**
     * 自动完成规则
     * @author zxq
     */
    protected $_auto = array(
        array('status', '0', self::MODEL_INSERT),
        array('dataline', '0', self::MODEL_INSERT),
        array('create_time', 'time', 1, 'function'),
        array('update_time', 'time', 2, 'function'),
    );

    /**
     *
     * @param $uid
     * @param $checkFree
     */
    public function isVip($uid,$sid=null,$checkFree = false) {
        $vipInfo = $this->find($uid);
        $onTime = time();
        if($vipInfo['status'] == 1 and ($onTime > $vipInfo['startdate']) and ($onTime < $vipInfo['enddate'])){
            // 我是vip
           return true;
        }else{
        	if($checkFree){
        		$sid = empty($sid) ? $post['sid'] : $sid;
        		$siteInfo = D('Love/Site')->find($sid);
				if($siteInfo['isfree'] == 1){//1 免费 0 否
					return $vipInfo ? $vipInfo : TRUE;
				}else{
					return false;
				}
        	}
           return false;
        }

    }
}
