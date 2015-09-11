<?php
namespace Management\Controller;
use Think\Controller;
class AdminController extends Controller {

    public function listUser(){

        $user     = M('user_xz');// 实例化Data数据模型
        $result     = $user->select();


        $this->assign('list',$result);
        $this->display();
    }

    public function listAdmin(){

    }

    public function addAdmin(){

    }

    public function deleteAdmin(){

    }
}