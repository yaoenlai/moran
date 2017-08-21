<?php
namespace Yyadmin\Controller;
use Think\Controller;

class AdminController extends Controller
{
    public function _initialize()
    {
        $uid = session('yuid');
        if(empty($uid))
        {
            $this->redirect("Public/login");
        }
        $this->assign('langs', array('CONTROLLER'=>L(CONTROLLER_NAME), 'ACTION'=>L(ACTION_NAME)));
    }
}

