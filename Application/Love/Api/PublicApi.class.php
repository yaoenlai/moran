<?php

// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------

namespace Love\Api;

use Love\Api\Api;
use Addons\Pay\ThinkPay\Pay;

/**
 * 聚合接口公共控制器
 * @author zxq
 */
class PublicApi extends Api {

    /**
     * 参数配置接口
     * 支持基础参数获取和地区参数获取
     * "ptname"=> "lunar",//不传返回全部，传错不反配置 传对则只反所传字段的配置
     * "ptname"=> "area",//area时，返回地区列表
     * "aid"=>1,//只有ptname=area时，该字段才有效，用于列出当前省份下的全部市
     * http://www.isgcn.com/api/Love/Public/paramter.api
     * @author
     */
    public function paramter() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            $paramter_ver = C('love_config.paramter_ver'); //服务端版本
            $object = D('Love/Paramter');
            $map['status'] = 1;
            if (empty($post['ptname'])) {//取全部
                if ($post['paramterVer'] != $paramter_ver) {
                    $data_list = $object->where($map)->order('id asc')->select();
                } else {
                    $this->ajaxReturn(returnInfo('-7', '暂无新版参数配置!', null, $this->infoType), $this->returnType);
                }
            } else {
                $map['ptname'] = $post['ptname'];
                $data_list[] = $object->where($map)->order('id asc')->find();
            }
            if (empty($data_list[0])) {
                if ($post['ptname'] = 'area') {//走获取地区逻辑
                    if (empty($post['aid'])) {
                        $where['rootid'] = 0;
                    } else {
                        $where['rootid'] = $post['aid'];
                    }
                    $areaInfo = D('Admin/Area')->where($where)->select();
                    $this->ajaxReturn(returnInfo('1', '获取地区列表成功!', $areaInfo, $this->infoType), $this->returnType);
                } else {
                    $this->ajaxReturn(returnInfo('-7', '获取参数配置为空!', null, $this->infoType), $this->returnType);
                }
            } else {
                foreach ($data_list as $key => $value) {
                    $data[$value['ptname']] = array(
                        'ptvalue' => \Common\Util\Think\Str::parseAttr($value['ptvalue']),
                        'ptdec' => $value['ptdec'],
                        'pttype' => $value['pttype'],
                    );
                }
                $this->ajaxReturn(returnInfo('1', '获取参数配置成功!', $data, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 网站信息接口
     * http://www.isgcn.com/api/game/Public/siteInfo.api
     * @author
     */
    public function siteInfo() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            $where['id'] = $post['sid'];
            $siteInfo = D('Love/Site')->where($where)->field('uid,title,ver,icon,isfree,splash,detail,copyright,about,protocol,key,backurl,status')->order('id desc')->find();

            if ($siteInfo) {
                if ($siteInfo['status'] == 1) {
                    $siteInfo['sid'] = $post['sid'];
                    $siteInfo['icon'] = get_cover($siteInfo['icon']);
                    $siteInfo['splash'] = get_cover($siteInfo['splash']);
                    if (empty($siteInfo['protocol']))
                        $siteInfo['protocol'] = C('love_config.protocol'); //系统默认注册协议
                    $this->ajaxReturn(returnInfo('1', '获取站点信息成功!', $siteInfo, $this->infoType), $this->returnType);
                }else {
                    $this->ajaxReturn(returnInfo('-7', '站点被封禁!', null, $this->infoType), $this->returnType);
                }
            } else {
                $this->ajaxReturn(returnInfo('-7', '站点不存在!', null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     * 网站信息接口
     * http://www.isgcn.com/api/game/Public/siteInfo.api
     * @author
     */
    public function agreement() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            $where['id'] = $post['sid'];
            $siteInfo = D('Love/Site')->where($where)->field('uid,title,ver,icon,isfree,splash,detail,copyright,about,protocol,key,backurl,status')->order('id desc')->find();

            if ($siteInfo) {
                if ($siteInfo['status'] == 1) {
                    if (empty($siteInfo['protocol']))
                        $siteInfo['protocol'] = C('love_config.protocol'); //系统默认注册协议
                    $this->ajaxReturn(returnInfo('1', '获取协议成功!', $siteInfo['protocol'], $this->infoType), $this->returnType);
                }else {
                    $this->ajaxReturn(returnInfo('-7', '站点被封禁!', null, $this->infoType), $this->returnType);
                }
            } else {
                $this->ajaxReturn(returnInfo('-7', '站点不存在!', null, $this->infoType), $this->returnType);
            }
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

    /**
     *  获取地区的下级列表
     * @param aid int  地区id
     * @return array 下级渠道列表
     * */
    public function getArea() {
        if ($this->verifySign()) {
            $post = $this->requestData;
            if (empty($post['aid']))
                $this->ajaxReturn(returnInfo('-7', '缺少必传参数aid!', null, $this->infoType), $this->returnType);

            $where['rootid'] = $post['aid'];
            $area = D('Admin/Area')->field('id,areaname')->where($where)->select();
            $data = array();
            if (!empty($area)) {
                foreach ($area as $key => $val) {
                    $data[] = array(
                        'aid' => $val['id'],
                        'areaName' => $val['areaname'],
                    );
                }
            }
            $this->ajaxReturn(returnInfo('1', '获取地区列表成功!', $data, $this->infoType), $this->returnType);
        } else {
            $this->ajaxReturn(returnInfo('-3', '验签失败!', null, $this->infoType), $this->returnType);
        }
    }

}
