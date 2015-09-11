<?php
namespace Management\Controller;
use Think\Controller;
class UserController extends Controller {

    public function listUser(){

        $user     = M('user_xz');// 实例化Data数据模型
        $result     = $user->select();


        $this->assign('list',$result);
        $this->display();
    }

    public function editUser(){

    }

    public function addUser(){

    }

    public function deleteUser(){

    }
}