<?php
// +----------------------------------------------------------------------
// | GamePlat [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.yongshihuyu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
use \Common\Util\Tree;

/**
 * 分类模型
 * @author zxq
 */
class AreaModel extends Model {
    /**
     * 模块名称
     * @author zxq
     */
    public $moduleName = 'Admin';

    /**
     * 数据库真实表名
     * 一般为了数据库的整洁，同时又不影响Model和Controller的名称
     * 我们约定每个模块的数据表都加上相同的前缀，比如微信模块用weixin作为数据表前缀
     * @author zxq
     */
    protected $tableName = 'admin_area';
	
	/**
     * 自动验证规则
     * @author sp
     */
    protected $_validate = array(
        array('rootid', 'require', '上级级别名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('areaname', 'require', '地区名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('spreadname', 'require', '级别名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('url', '0,255', '链接长度为0-25个字符', self::EXISTS_VALIDATE, 'length',self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author sp
     */
    protected $_auto = array(
        array('depth', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
        array('orders', '0', self::MODEL_INSERT),
        array('tabstatus', '0', self::MODEL_INSERT),
        array('elite', '0', self::MODEL_INSERT),
    );

    /*
     * 将列表信息转化成树状结构
     * @param $list array 地区信息
     * @param $top boolean 是否查询省级地区
     */
    public function areaListToTree($data,$top=false){
        if( $top || !$data[0]['rootid'] ){
            $map['rootid'] = $data[0]['id'];
            $data_list = $this ->field('id,areaname,rootid,depth,status,spreadname')
                ->where($map)
                ->order('id')
                ->select();
            array_unshift($data_list,$data[0]);
            unset($data);
            // 转换成树状列表
            $tree = new Tree();
            $data = $tree->toFormatTree($data_list,'areaname','id','rootid');
            foreach ($data as $key=>&$value ){
                $value['areaname'] = $value['areaname'].$value['spreadname'];
                if( !empty($value['rootid']) ){
                    $value['areaname'] = $value['title_show'].$value['spreadname'];
                }
            }
        }

        unset($list);unset($data_list);
        return $data;
    }

    /*
     * 将列表信息转化成树状结构，主要适用于下拉框
     * @param $rootid boolean 判断是否需要输出省级以下地区，若不需要则$rootid=false，否则为true
     * @param $type boolean 判断返回键值格式，如果type=false,返回$data['区域ID']，比如$data['1']=array(.....)；
     *                                        否则返回$data['升级区域ID-市级区域ID']，比如$data['1-2']=array(.....)；
     */
    public function areaTreeForSelect($rootid=false,$type=false){
        if( empty($rootid) ){
            $map['rootid'] = $rootid;
        }
        $list = $this->field('id,areaname,rootid,spreadname')->where($map)->order('id')->select();
        // 转换成树状列表
        $tree = new Tree();
        $data = $tree->toFormatTree($list,'areaname','id','rootid');
        foreach ($data as $key=>$value ){
            $value['areaname'] = $value['areaname'];
            if( !empty($value['rootid']) ){
                $value['areaname'] = $value['title_show'];
            }
            if($type){
                $data_list[$value['rootid'].'-'.$value['id']] = $value['areaname'];
            }else{
                $data_list[$value['id']] = $value['areaname'];
            }
        }
        unset($data);
        return $data_list;
    }

    /*
     * 获取用户择友所在区域
     */
    public function getArea($area){
        $area = unserialize($area);
        $data = $this->field('id,areaname,spreadname')->select();
        foreach ( $data as $key=>$value ){
            $data_list[$value['id']] = $value;
        }
        foreach ( $area as $k=>$v ){
            //返回数组键值格式：排序-上级区域ID-下级区域ID   比如：1-2-3
            $area_list[$v['orders'].'-'.$v['province'].'-'.$v['city']] = $data_list[$v['province']]['areaname'].'●'.$data_list[$v['city']]['areaname'];
        }
        unset($data);unset($area);
        ksort($area_list);
        return $area_list;
    }

    /*
     * 地区联动（目前支持二级联动）
     */
    public function linkage(){
        $data_list = $this->field('id,areaname,rootid,spreadname')->order('id')->select();
        foreach( $data_list as $key=>$value ){
            $list[0]['p'] = '请选择';
            if( $value['rootid']==0 ){
                $list[$value['id']]['p'] = $value['id'].':'.$value['areaname'];
            }else{
                $list[$value['rootid']]['c'][] = array(
                    'n' => $value['id'].':'.$value['areaname'],
                );
            }
        }
        $list = array_values($list);
        //var_dump($list);
        $data['self']['citylist'] = $list;
        unset($list);unset($data_list);
        return $data;
    }
	


}
