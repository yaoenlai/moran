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
class ParamterModel extends Model {
    /**
     * 数据库表名
     * @author zxq
     */
    protected $tableName = 'love_paramter';

    /**
     * 自动验证规则
     * @author zxq
     */
    protected $_validate = array(
        array('ptname', 'require', '字段名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('ptname', '', '字段名称被占用', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
        array('ptvalue', 'require', '字段选项不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('ptdec', 'require', '字段描述不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('pttype', 'require', '字段类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /*
     * 获取指定的交友资料信息
     * @param $ptname 字段名
     */
    public function getParamter($ptname=''){
        $map['status'] = 1;
        if( !empty($ptname) ){
            $map['ptname'] = array('eq',$ptname);
            $data_list[] = $this->where($map)->order('id asc')->find();
        }else{
            $data_list = $this->where($map)->order('id asc')->select();
        }

        foreach ($data_list as $k => $v) {
            $ptvalue = \Common\Util\Think\Str::parseAttr($v['ptvalue']);
            //var_dump($ptvalue);exit;
            //循环处理每条数据里面的ptvalue信息，组装为新数组
            foreach( $ptvalue as $kk=>$vv ){
                $data[$v['ptname']][$kk] = array(
                    'title' => $vv,
                );
            }
        }
        unset($data_list);
        return $data;
    }
}
