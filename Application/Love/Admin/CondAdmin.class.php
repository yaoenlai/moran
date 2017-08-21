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
 * 用户择友控制器
 * @author sp
 */
class CondAdmin extends CommonAdmin {
    /*
     * 查看用户择友
     */
    public function index(){
        if( IS_POST ){
            $object = D("Love/Cond");
            //对所在区域和优先区域的值进行特殊处理
            $set_area = I("setarea",'');
            if( is_array($set_area) && !empty($set_area) ){
                foreach( $set_area as $key=>$value ){
                    $set_area_arr = explode('-',$value);
                    $_POST['set_area'][] = array(
                        'orders' => $set_area_arr[0],
                        'province' => $set_area_arr[1],
                        'city' => $set_area_arr[2],
                    );
                }
            }
            unset($_POST['setarea']);
            $_POST['setarea'] = serialize($_POST['set_area']);

            $areas = I("areas",'');
            if( !empty($areas) ){
                $areas_arr = explode(':',$areas);
                $_POST['areas'] = $areas_arr[0];
            }
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
            $object = D('Love/Cond');
            $info = $object->find($id);
            if( empty($info) ){
                $info['uid'] = $id;
                $info['remark_status'] = 0;   //备注用户择友添加状态，如果之前没有添加过则备注状态为0，执行新增状态
            }else{
                $info['remark_status'] = 1;   //备注用户择友添加状态，如果之前添加过则备注状态为1，执行更新操作
            }

            $data = D('Love/Paramter')->getParamter();
            $area_list = D('Admin/Area')->getArea($info['setarea']);
            $area_select = D('Admin/Area')->areaTreeForSelect(true,true);
            //var_dump($area_list);
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('用户择友')  // 设置页面标题
                ->setPostUrl(U(''))    // 设置表单提交地址
                ->addFormItem('uid', 'hidden', 'ID', 'ID')
                ->addFormItem('remark_status', 'hidden', '备注用户隐私添加状态', '备注用户隐私添加状态')   //备注用户择友添加状态
                ->addFormItem('gender', 'select', '性别', '性别', $data['sex'])
                ->addFormItem('startage', 'text', '最小年龄', '最小年龄')
                ->addFormItem('endage', 'text', '最大年龄', '最大年龄')
                ->addFormItem('startheight', 'text', '最小体重', '最小体重')
                ->addFormItem('endheight', 'text', '最大体重', '最大体重')
                ->addFormItem('marry', 'select', '婚姻状态', '婚姻状态',$data['marrystatus'])
                ->addFormItem('lovesort', 'select', '交友类型', '交友类型',$data['lovesort'])
                ->addFormItem('startedu', 'select', '最低学历', '最低学历',$data['education'])
                ->addFormItem('endedu', 'select', '最高学历', '最高学历',$data['education'])
                ->addFormItem('select_setarea', 'select', '请选择所在区域', '请选择所在区域',$area_select)
                ->addFormItem('setarea', 'checkbox', '所在区域', '所在区域',$area_list)
                ->addFormItem('select_areas', 'select', '请选择优先区域', '请选择优先区域',$area_select)
                ->addFormItem('areas', 'text', '优先区域', '优先区域')
                ->addFormItem('house', 'select', '住房要求', '住房要求',$data['housing'])
                ->addFormItem('car', 'select', '购车需求', '购车需求',$data['caring'])
                ->addFormItem('avatar', 'radio', '图像', '图像',array('1'=>'是','2'=>'不限'))
                ->addFormItem('star', 'text', '星级', '星级')
                ->addFormItem('starup', 'radio', '星级判断', '星级判断',array('1'=>'以上','2'=>'以下'))
                //->addFormItem('status', 'text', '状态', '状态')
                ->addFormItem('mustcond', 'text', '必须条件', '必须条件')
                ->setFormData($info)
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    private $extra_html = <<<EOF
    <script type="text/javascript">
        //选择回复类型的时候改变页面元素
        $(function(){
            $(document).ready(function(){ 
                //默认选中所有的所选区域
                $('.item_setarea .right').find("input[type='checkBox']").each(function () {
                    $(this).attr('checked','checked');
                })
                
                //默认选中优先区域
                var input = $('.item_areas input').val();   //获取优先区域默认值
                $("select[name='select_areas'] option").each(function(){
                    var text = $(this).text();
                    text = input+":"+text.split("┝")[1];
                    var val = $(this).val();
                    var ret = new RegExp("\-"+input+"$","gim");
                    if( ret.test(val) ){
                        $('.item_areas input').val(text);
                    }
                });
            });
            
            //添加所在区域
            $('select[name="select_setarea"]').change(function(){
                var type = $(this).val();
                var area_id = type.split("-");
                var prov_id = area_id[0];
                var city_id = area_id[1];
                var prov_name = $("option[value=0-"+prov_id+"]",this).text();
                var city_name = $("option[value="+prov_id+'-'+city_id+"]",this).text();
                city_name = city_name.split("┝")[1];
                //获取所在区域数量
                var num = $('.item_setarea .right').children().length;
                if( num<5 ){
                    var dom = "<div class='checkbox-inline cui-control cui-checkbox'><label class='checkbox-label'><input checked='checked' type='checkbox' name='setarea[]' value='"+(num+1)+"-"+prov_id+"-"+city_id+"'><span class='cui-control-indicator'></span><span>"+prov_name+"●"+city_name+"</span></label></div>"
                    $('.item_setarea .right').append(dom);
                }else{
                    alert("所在区域最多只能添加5个");
                }
            });
            
            //添加优选区域
            $('select[name="select_areas"]').change(function(){
                var type = $(this).val();
                var area_id = type.split("-");
                var prov_id = area_id[0];
                var city_id = area_id[1];
                var prov_name = $("option[value=0-"+prov_id+"]",this).text();
                var city_name = $("option[value="+prov_id+'-'+city_id+"]",this).text();
                city_name = city_name.split("┝")[1];
                $('.item_areas input').val(city_id+':'+city_name);
            });
        });
    </script>
EOF;
}