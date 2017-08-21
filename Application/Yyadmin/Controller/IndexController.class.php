<?php
namespace Yyadmin\Controller;

class IndexController extends AdminController
{
    
    public function index(){
	   $this->display();
    }
    
    public function main(){
        $this->display();
    }
}
