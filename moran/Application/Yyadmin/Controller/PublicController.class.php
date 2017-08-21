<?php
namespace Yyadmin\Controller;
use Think\Controller;
class PublicController extends Controller
{
    //登录用户
    public function login($username = null, $password = null, $verify = null)
    {
        if(IS_POST)
        {
            $model = D("YyadminUser");
            $uid = $model->login($username, $password);
            if(0 < $uid){ //UC登录成功
                //TODO:跳转到登录前页面
                $this->success('登录成功！', U('Index/index'));
            } else {
                $this->error($model->getError());
            }        
        } else {
            $this->display();
        }
    }
    
    //注销用户
    public function logout()
    {
        session('yuid', null);
        $this->redirect("Public/login");
    }
    
    //获取栏目列表
    public function nav() 
    {
        $data = array(
            array(
                "title" => "网站信息",
            	"icon"  => "fa-cubes",
            	"href"  => U('info/index'),
	            "spread"=> false,
            ),
            array(
                "title" => "汇总管理",
                'icon'  => "fa-cubes",
                "spread"=> false,
                "children" => array(
                    array(
                        "title"   => "汇总列表",
                        "icon"    => "&#xe63c;",
                        "href"    => U('Count/index'),
                    ),
                ),
            ),
            array(
                "title" => "用户管理",
                'icon'  => "fa-cubes",
                "spread"=> false,
                "children" => array(
                    array(
                        "title"   => "用户列表",
                        "icon"    => "&#xe63c;",
                        "href"    => U('User/index'),
                    ),
                ),
            ),            
    	);
        $this->ajaxReturn($data, 'json');
    }
}