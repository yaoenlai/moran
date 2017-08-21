<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Love\Admin;
use Admin\Controller\AdminController;
use Common\Util\Think\Page;
/**
 * 用户隐私控制器
 * @author sp
 */
class AttrAdmin extends CommonAdmin {
    /*
     * 查看用户隐私
     */
    public function index(){
        $id = I('id','','int');
        if( IS_POST ){
            $object = D('Love/Attr');
            $data = $object->create();
            if( $data ){
                if( empty(I('remark_status','','int')) ){
                    $id = $object->add();
                    $str = '新增';
                }else{
                    $str = '编辑';
                    $id = $object->save();
                }
                if ($id) {
                    $this->success($str.'成功', U('Love/User/Index'));
                } else {
                    $this->error($str.'失败');
                }
            }else{
                $this->error($object->getError());
            }
        }else{
            $id = I('id','','int');
            $object = D('Love/Attr');
            $info = $object->find($id);
            if( empty($info) ){
                $info['uid'] = $id;
                $info['remark_status'] = 0;   //备注用户隐私添加状态，如果之前没有添加过则备注状态为0，执行新增状态
            }else{
                $info['remark_status'] = 1;   //备注用户隐私添加状态，如果之前添加过则备注状态为1，执行更新操作
            }

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('用户隐私')  // 设置页面标题
                ->setPostUrl(U('index',array('id'=>$id)))    // 设置表单提交地址
                ->addFormItem('uid', 'hidden', 'ID', 'ID')
                ->addFormItem('remark_status', 'hidden', '备注用户隐私添加状态', '备注用户隐私添加状态')   //备注用户隐私添加状态
                ->addFormItem('privacy', 'radio', '私密方式', '私密方式', array('0'=>'会员可见','4'=>'保密'))
                ->addFormItem('realname', 'text', '真实姓名', '真实姓名')
                ->addFormItem('idnumber', 'text', '身份证号', '身份证号')
                ->addFormItem('telephone', 'text', '电话号码', '电话号码')
                ->addFormItem('mobile', 'text', '手机号码', '手机号码')
                ->addFormItem('qq', 'text', 'QQ号码', 'QQ号码')
                ->addFormItem('msn', 'text', 'MSN号码', 'MSN号码')
                ->addFormItem('address', 'text', '地址', '地址')
                ->addFormItem('zipcode', 'text', '邮编', '邮编')
                ->addFormItem('skype', 'text', 'Skype', 'Skype')
                ->addFormItem('homepage', 'text', '主页', '主页')
                ->addFormItem('facebook', 'text', 'Facebook', 'Facebook')
                ->addFormItem('status', 'text', '状态', '状态')
                ->addFormItem('recall', 'radio', '是否验证', '是否验证',array('0'=>'未验证','1'=>'已验证'))
                ->addFormItem('attrs', 'text', '属性', '属性')
                ->setFormData($info)
                ->display();
        }
    }
}