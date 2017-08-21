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
 * 用户资料控制器
 * @author zxq
 */
class ProfileAdmin extends CommonAdmin {
    public function index(){
        if( IS_POST ){
            $object = D('Love/Profile');
            $_POST['moluptime'] = strtotime($_POST['moluptime']);
            $_POST['schoolyear'] = strtotime($_POST['schoolyear']);
            $data = $object->create();
            if( $data ){
                if( empty(I('remark_status','','int')) ){
                    $id = $object->add();
                    $str = '新增';
                }else{
                    $id = $object->save();
                    $str = '编辑';
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
            $object = D('Love/Profile');
            $info = $object->find($id);
            if( empty($info) ){
                $info['uid'] = $id;
                $info['remark_status'] = 0;   //备注用户资料添加状态，如果之前没有添加过则备注状态为0，执行新增状态
            }else{
                $info['remark_status'] = 1;   //备注用户资料添加状态，如果之前添加过则备注状态为1，执行更新操作
            }

            $data = D('Love/Paramter')->getParamter();
            $area_select = D('Admin/Area')->areaTreeForSelect(true);

            //联动已经写好（只满足二级联动且无法显示之前已保存的省市信息，后期优化后在上线）
            //$area_select = D('Admin/Area')->linkage();

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('用户资料')  // 设置页面标题
                ->setPostUrl(U('index'))    // 设置表单提交地址
                ->addFormItem('uid', 'hidden', 'ID', 'ID')
                ->addFormItem('remark_status', 'hidden', '备注用户资料添加状态', '备注用户资料添加状态')     //备注用户资料添加状态
                //->addFormItem('area_link', 'linkage', '省份ID', '省份ID',null,$area_select)
                ->addFormItem('provinceid', 'select', '省级ID', '省级ID',$area_select)
                ->addFormItem('cityid', 'select', '城市ID', '城市ID',$area_select)
                ->addFormItem('distid', 'num', '区域ID', '区域ID')
                ->addFormItem('communityid', 'num', '社区ID', '社区ID')
                ->addFormItem('lovesort', "select", '交友类型', '交友类型',$data['lovesort'])
                ->addFormItem('ageyear', 'num', '出生年', '出生年(四位)')
                ->addFormItem('agemonth', 'num', '出生月', '出生月')
                ->addFormItem('ageday', 'num', '出生日', '出生日')
                ->addFormItem('birthday', 'date', '生日', '生日')
                ->addFormItem('birthdaylock', 'radio', '生日是否已锁定', '生日是否已锁定',array('0'=>'否','1'=>'是'))
                ->addFormItem('astro', 'select', '星座', '星座',$data['astro'])
                ->addFormItem('lunar', 'text', '生效', '生效')
                ->addFormItem('marrystatus', 'select', '婚姻状态', '婚姻状态',$data['marrystatus'])
                ->addFormItem('blood', 'select', '血型', '血型',$data['blood'])
                ->addFormItem('childrenstatus', 'select', '孩子状态', '孩子状态',$data['childrenstatus'])
                ->addFormItem('education', 'select', '学历', '学历',$data['education'])
                ->addFormItem('height', 'text', '身高', '身高（单位：cm）')
                ->addFormItem('national', 'select', '民族', '民族',$data['national'])
                ->addFormItem('jobs', 'select', '职业', '职业',$data['jobs'])
                ->addFormItem('salary', 'select', '月收入', '月收入',$data['salary'])
                ->addFormItem('housing', 'select', '住房情况', '住房情况',$data['housing'])
                ->addFormItem('school', 'text', '毕业学校', '毕业学校')
                ->addFormItem('schoolyear', 'date', '入学年份', '入学年份')
                ->addFormItem('specialty', 'select', '专业类型', '专业类型',$data['specialty'])
                ->addFormItem('personality', 'select', '个性', '个性',$data['personality'])
                ->addFormItem('weight', 'text', '体重(Kg)', '体重(Kg)')
                ->addFormItem('profile', 'select', '外貌自评', '外貌自评',$data['profile'])
                ->addFormItem('charmparts', 'select', '魅力部位', '魅力部位',$data['charmparts'])
                ->addFormItem('hairstyle', 'select', '发型', '发型',$data['hairstyle'])
                ->addFormItem('haircolor', 'select', '发色', '发色',$data['haircolor'])
                ->addFormItem('facestyle', 'select', '脸型', '脸型',$data['facestyle'])
                ->addFormItem('bodystyle', 'select', '体型', '体型',$data['bodystyle'])
                ->addFormItem('companytype', 'select', '公司类型', '公司类型',$data['companytype'])
                ->addFormItem('income', 'select', '收入描述', '收入描述',$data['income'])
                ->addFormItem('companyname', 'text', '公司名称', '公司名称')
                ->addFormItem('workstatus', 'select', '工作状况', '工作状况',$data['workstatus'])
                ->addFormItem('nationality', 'select', '国籍', '国籍',$data['nationality'])
                ->addFormItem('nationprovinceid', 'text', '国家ID', '国家ID')
                ->addFormItem('nationcityid', 'text', '国家城市ID', '国家城市ID')
                ->addFormItem('beforeregion', 'select', '曾经留学/居住地区', '曾经留学/居住地区',$data['beforeregion'])
                ->addFormItem('caring', 'select', '购车情况', '购车情况',$data['caring'])
                ->addFormItem('consume', 'select', '最大消费', '最大消费',$data['consume'])
                ->addFormItem('tophome', 'select', '家中排行', '家中排行',$data['tophome'])
                ->addFormItem('smoking', 'select', '吸烟情况', '吸烟情况',$data['smoking'])
                ->addFormItem('drinking', 'select', '饮酒情况', '饮酒情况',$data['drinking'])
                ->addFormItem('language', 'select', '语言情况', '语言情况',$data['language'])
                ->addFormItem('faith', 'select', '宗教信仰', '宗教信仰',$data['faith'])
                ->addFormItem('workout', 'select', '锻炼情况', '锻炼情况',$data['workout'])
                ->addFormItem('rest', 'select', '作息习惯', '作息习惯',$data['rest'])
                ->addFormItem('leisure', 'select', '娱乐休闲', '娱乐休闲',$data['leisure'])
                ->addFormItem('lifeskill', 'select', '生活技能', '生活技能',$data['lifeskill'])
                ->addFormItem('talive', 'select', '愿与对方父母同住', '愿与对方父母同住',$data['talive'])
                ->addFormItem('havechildren', 'seelct', '是否要小孩', '是否要小孩',$data['havechildren'])
                ->addFormItem('romantic', 'select', '制造浪漫', '制造浪漫',$data['romantic'])
                ->addFormItem('interest', 'select', '兴趣爱好', '兴趣爱好',$data['interest'])
                ->addFormItem('attention', 'select', '最近关注', '最近关注',$data['attention'])
                ->addFormItem('food', 'select', '喜欢的食物', '喜欢的食物',$data['food'])
                ->addFormItem('sports', 'select', '喜欢的运动', '喜欢的运动',$data['sports'])
                ->addFormItem('film', 'select', '喜欢的电影', '喜欢的电影',$data['film'])
                ->addFormItem('travel', 'select', '喜欢的旅游去处', '喜欢的旅游去处',$data['travel'])
                ->addFormItem('book', 'select', '喜欢的书籍', '喜欢的书籍',$data['book'])
                ->addFormItem('monolog', 'select', '内心独白', '内心独白',$data['monolog'])
                ->addFormItem('molstatus', 'select', '内心独白是否已写', '内心独白是否已写',$data['molstatus'])
                ->addFormItem('moluptime', 'date', '何时开始单身', '何时开始单身')
                ->addFormItem('remark', 'text', '评论', '评论')
                ->addFormItem('banyoutype', 'select', '伴游类型', '伴游类型',$data['banyoutype'])
                ->addFormItem('banyoupay', 'select', '伴游报酬', '伴游报酬',$data['banyoupay'])
                ->addFormItem('banyoustime', 'select', '方便联系最早时间', '方便联系最早时间',$data['banyoustime'])
                ->addFormItem('banyouetime', 'select', '方便联系最晚时间', '方便联系最晚时间',$data['banyouetime'])
                ->addFormItem('banyoupassport', 'select', '伴游护照情况', '伴游护照情况',$data['banyoupassport'])
                ->addFormItem('banyouarea', 'select', '伴游地区', '伴游地区',$data['banyouarea'])
                ->addFormItem('banyouundergo', 'select', '伴游经验', '伴游经验',$data['banyouundergo'])
                ->setFormData($info)
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    private $extra_html = <<<EOF
    <script type="text/javascript">
//        //选择回复类型的时候改变页面元素
        $(function(){
            $(document).ready(function(){
                //去除省级下拉框中市级选项
                $("select[name='provinceid'] option").each(function(){
                    var val = $(this).text();
                    var ret = new RegExp("\┝","gim");
                    if( ret.test(val) ){
                        $(this).remove();
                    }
                });
                
                //去除市级下拉框省级选项不可选
                $("select[name='cityid'] option").each(function(){
                    var val = $(this).text();
                    var ret = new RegExp("\┝","gim");
                    if( !ret.test(val) ){
                        $(this).attr("disabled",true);
                    }
                });
            });
//            
//            $("select[name='provinceid']").change(function(){
//                //获取已经选定的省份ID
//                var prov_id = $(this).val();
//                //根据已选定省份ID，通过正则删除不属于该省的市级地区
//                $("select[name='cityid'] option").each(function(){
//                    var val = $(this).val();
//                    var ret = new RegExp("^"+prov_id+"\-","gim");
//                    if( !ret.test(val) ){
//                        $(this).remove();
//                    }
//                });
//            });
        });
    </script>
EOF;
}
