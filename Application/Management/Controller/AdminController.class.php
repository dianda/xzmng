<?php
namespace Management\Controller;
use Think\Controller;
class AdminController extends Controller {

    public function listUser()
    {
        error_log('asdasda');
        $area=D('area_xz');
        $resultarea=$area->select();
        $jg=D('jgdm_xz');
        $resultjg=$jg->select();
error_log($jg->getlastsql());
        $page=new \Think\Lpage();
        $page->pageSize='10';  
        $page->pageNumber='6';

        $page->setTables('user_xz a,jgdm_xz b,area_xz c');
        $page->setColumns('a.XM,a.SFZJLX ,a.SFZJH,a.GH,b.NAME as JG,a.EMAIL,a.TEL,c.NAME as QX');
        $page->setOrders('a.ID desc');

        $page->setField(1,'a.JGDM');
        $page->setOperation(1,'=');
        $page->setCondition(1,'b.ID');

        $page->setField(2,'a.AREA');
        $page->setOperation(2,'=');
        $page->setCondition(2,'c.ID');

        $page->setField(3,'a.AREA');
        $page->setOperation(3,'=');
        $page->setCondition(3,$_POST['condition1']);

        $page->setField(3,'a.JGDM');
        $page->setOperation(3,'=');
        $page->setCondition(3,$_POST['condition2']);

        $page->setField(3,'a.JGDM');
        $page->setOperation(3,'like');
        $page->setCondition(3,$_POST['condition3']);

        $pager = $page->pageShow();

        $this->assign('area',$resultarea);
        $this->assign('jg',$resultjg);
        $this->assign('data',$page->list);// 赋值数据集
        $this->assign('page',$pager);
        $this->display();

    }

    public function listAdmin(){

    }

    public function addAdmin(){

    }

    public function deleteAdmin(){

    }
}