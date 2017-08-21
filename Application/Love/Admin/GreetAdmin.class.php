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
 * 用户控制器
 * @author zxq
 */
class GreetAdmin extends CommonAdmin {
	    // 文档类型切换触发操作JS
        private $extra_html = <<<EOF
        <style type="text/css">
         .upload_hide{height: 0;overflow: hidden;margin-bottom: 0;}
        </style>
        <script type="text/javascript">
            //选择招呼类型时页面元素改变
            $(function() {
                $('input[name="greet_type"]').change(function() {
                    var model_id = $(this).val();
                    if (model_id == 1) { //单句招呼
                        $('.item_greeting').removeClass('hidden');
                        $('.item_greetans').addClass('hidden');
                    } else if (model_id == 2) { //内置答案
                        $('.item_greeting').removeClass('hidden');
                        $('.item_greetans').removeClass('hidden');
                    } else {//页面刷新显示全部
                        $('.item_greeting').removeClass('hidden');
                        $('.item_greetans').removeClass('hidden');
                    }
                });
            });
            //选择消息类型时页面元素改变
            $(function() {
                $('input[name="media_type"]').change(function() {
                    var model_id = $(this).val();
                    if (model_id == 1) { //图片
                        $('.item_picture').removeClass('upload_hide');
                        $('.item_voice').addClass('upload_hide');
                        $('.item_video').addClass('upload_hide');
                    } else if (model_id == 2) { //语音
                        $('.item_picture').addClass('upload_hide');
                        $('.item_voice').removeClass('upload_hide');
                        $('.item_video').addClass('upload_hide');
                    } else if (model_id == 3){//视频
                        $('.item_picture').addClass('upload_hide');
                        $('.item_voice').addClass('upload_hide');
                        $('.item_video').removeClass('upload_hide');
                    } else {//无
                        $('.item_picture').addClass('upload_hide');
                        $('.item_voice').addClass('upload_hide');
                        $('.item_video').addClass('upload_hide');
                    }
                });
            });
        </script>
EOF;
	private $greet_type = array(1=>'单句招呼',2=>'内置答案');
	private $media_type = array('0'=>'无',1=>'图片',2=>'语音',3=>'视频');
	
    /**
     * 用户列表
     * @author zxq
     */
    public function index() {
        // 搜索
        $keyword   = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|greeting|greetans'] = array( 
            $condition,
            $condition,
            $condition,
            '_multi'=>true
        );

        // 获取所有用户
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $object = D('Love/Greet');
        $data_list = $object
                   ->page($p , C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id desc')
                   ->select();
        $page = new Page(
            $object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('招呼列表') // 设置页面标题
                ->addTopButton('addnew')  // 添加新增按钮
                ->addTopButton('resume')  // 添加启用按钮
                ->addTopButton('forbid')  // 添加禁用按钮
                ->setSearch('请输入ID/招呼内容', U('index'))
                ->addTableColumn('id', 'ID')
                //->addTableColumn('ptname', '头像', 'picture')
                ->addTableColumn('male', '男?','status')
                ->addTableColumn('female', '女?','status')
                ->addTableColumn('greet_type', '类型','diy_status',$this->greet_type)
                ->addTableColumn('greeting', '招呼内容','closing_tag',array('left'=>'<pre style="max-width:150px;padding:9.5px 0;">','right'=>'</pre>'))
				->addTableColumn('greetans', '招呼答案','closing_tag',array('left'=>'<pre style="max-width:150px;padding:9.5px 0;">','right'=>'</pre>'))
                ->addTableColumn('ask_type', '追数')
                ->addTableColumn('media_type', '媒体','diy_status',$this->media_type)
				->addTableColumn('issystem', '内置?','diy_status',$this->boolean)
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('create_time', '创建时间', 'date')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)    // 数据列表
                ->setTableDataPage($page->show()) // 数据列表分页
                ->addRightButton('edit')          // 添加编辑按钮
                ->addRightButton('forbid')        // 添加禁用/启用按钮
                ->display();
    }

    /**
     * 新增用户
     * @author zxq
     */
    public function add() {
        if (IS_POST) {
            $object = D('Love/Greet');
            $data = $object->create();
            if ($data) {
                $id = $object->add();
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增招呼') //设置页面标题
                    ->setPostUrl(U('add'))    //设置表单提交地址
                    ->addFormItem('male', 'radio', '可否发给男生?', '该招呼是否可用于发给男生',$this->boolean)
                    ->addFormItem('female', 'radio', '可否发给女生?', '该招呼是否可用于发给女生',$this->boolean)
                    ->addFormItem('greet_type', 'radio', '分类内容模型', '分类内容模型', $this->greet_type)
                    ->addFormItem('greeting', 'text', '招呼内容', '一句回复招呼语句')
                    ->addFormItem('greetans', 'textarea', '招呼答案', '格式(喜欢:我很喜欢你这样的性格)', null, 'hidden')
                    ->addFormItem('ask_type', 'radio', '第几追', '属于第几追问语句', range(0,8))
                    ->addFormItem('media_type', 'radio', '媒体分类', '是否含有媒体',$this->media_type)
                    ->addFormItem('picture', 'picture', '图片', '招呼含有图片媒体时', null, 'upload_hide')
                    ->addFormItem('voice', 'media', '语音', '招呼含有语音媒体时', null, 'upload_hide')
                    ->addFormItem('video', 'media', '视频', '招呼含有视频媒体时', null, 'upload_hide')
                    ->addFormItem('issystem', 'radio', '是否系统分类', '是否系统分类', $this->boolean)
					->setFormData(array('female'=>1,'issystem'=>0,'greet_type'=>1))
                    ->setExtraHtml($this->extra_html)
                    ->display();
        }
    }

    /**
     * 编辑用户
     * @author zxq
     */
    public function edit($id) {
        if (IS_POST) {
            // 提交数据
            $object = D('Love/Greet');
            $data = $object->create();
            if ($data) {
                $result = $object->save($data);
                if ($result) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败', $object->getError());
                }
            } else {
                $this->error($object->getError());
            }
        } else {
            // 获取账号信息
            $info = D('Love/Greet')->find($id);

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑预设信息')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('male', 'radio', '可否发给男生?', '该招呼是否可用于发给男生',$this->boolean)
                    ->addFormItem('female', 'radio', '可否发给女生?', '该招呼是否可用于发给女生',$this->boolean)
                    ->addFormItem('greet_type', 'radio', '分类内容模型', '分类内容模型', $this->greet_type)
                    ->addFormItem('greeting', 'text', '招呼内容', '一句回复招呼语句')
                    ->addFormItem('greetans','textarea','招呼答案','格式(喜欢:我喜欢)',null,$info['greet_type']==2 ?: 'hidden')
                    ->addFormItem('ask_type', 'radio', '第几追', '属于第几追问语句', range(0,8))
                    ->addFormItem('media_type', 'radio', '媒体分类', '是否含有媒体',$this->media_type)
                    ->addFormItem('picture', 'picture', '图片', '招呼含有图片媒体时', null, $info['media_type'] == 1 ? : 'upload_hide')
                    ->addFormItem('voice', 'media', '语音', '招呼含有语音媒体时',null, $info['media_type'] == 2 ? : 'upload_hide')
                    ->addFormItem('video', 'media', '视频', '招呼含有视频媒体时',null, $info['media_type'] == 3 ? : 'upload_hide')
                    ->addFormItem('issystem', 'radio', '是否系统分类', '是否系统分类', $this->boolean)
					->setExtraHtml($this->extra_html)
                    ->setFormData($info)
                    ->display();
        }
    }

}
