<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: sp
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
use \Common\Util\Tree;
/**
 * 地区模型
 * @author sp
 */
class DeviceModel extends Model {
    /**
     * 数据库表名
     * @author sp
     */
    protected $tableName = 'admin_device';

    /**
     * 自动验证规则
     * @author sp
     */
    protected $_validate = array(
        //array('rootid', 'require', '上级级别名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        //array('areaname', 'require', '地区名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('spreadname', 'require', '级别名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        //array('url', '0,255', '链接长度为0-25个字符', self::EXISTS_VALIDATE, 'length',self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author sp
     */
    protected $_auto = array(
        //array('depth', '0', self::MODEL_INSERT),
        //array('status', '1', self::MODEL_INSERT),
        //array('orders', '0', self::MODEL_INSERT),
        //array('tabstatus', '0', self::MODEL_INSERT),
        //array('elite', '0', self::MODEL_INSERT),
    );

    /*
     * 将列表信息转化成树状结构
     */
    public function areaListToTree($list){
        // 转换成树状列表
        $tree = new Tree();
        $data = $tree->toFormatTree($list,'areaname','id','rootid');
        foreach ($data as $key=>&$value ){
            $value['areaname'] = $value['areaname'].$value['spreadname'];
            if( !empty($value['rootid']) ){
                $value['areaname'] = $value['title_show'].$value['spreadname'];
            }
        }

        return $data;
    }

    /*
     * 将列表信息转化成树状结构，主要适用于下拉框
     */
    public function areaTreeForSelect(){
        $map['rootid'] = 0;
        $list = $this->field('id,areaname,rootid,spreadname')->where($map)->order('id')->select();
        // 转换成树状列表
        $tree = new Tree();
        $data = $tree->toFormatTree($list,'areaname','id','rootid');
        foreach ($data as $key=>$value ){
            $value['areaname'] = $value['areaname'].$value['spreadname'];
            if( !empty($value['rootid']) ){
                $value['areaname'] = $value['title_show'].$value['spreadname'];
            }

            $data_list[$value['id']] = $value['areaname'];
        }
        unset($data);
        return $data_list;
    }
}
