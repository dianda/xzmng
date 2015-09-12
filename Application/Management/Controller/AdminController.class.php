<?php
namespace Management\Controller;
use Think\Controller;
class AdminController extends Controller {
    public function listUser()
    {
        $area=D('area_xz');
        $resultarea=$area->select();
        $jg=D('jgdm_xz');
        $resultjg=$jg->select();
        $page=new \Think\Lpage();
        $page->pageSize='10';
        $page->pageNumber='6';

        $page->setTables('user_xz a,jgdm_xz b,area_xz c');
        $page->setColumns('a.ID,a.XM,a.SFZJLX ,a.SFZJH,a.GH,b.NAME as JG,a.EMAIL,a.TEL,c.NAME as QX');
        $page->setOrders('a.ID desc');

        $page->setPrecon('a.JGDM=b.ID&&a.AREA=c.ID');
        $page->setField(1,'a.AREA');
        $page->setOperation(1,'=');
        $page->setCondition(1,$_POST['condition1']);

        $page->setField(2,'a.JGDM');
        $page->setOperation(2,'=');
        $page->setCondition(2,$_POST['condition2']);

        $page->setField(3,'a.XM');
        $page->setOperation(3,'like');
        $page->setCondition(3,$_POST['condition3']);

        $pager = $page->pageShow();

        $this->assign('area',$resultarea);
        $this->assign('jg',$resultjg);
        $this->assign('data',$page->list);
        $this->assign('page',$pager);
        $this->display();

    }





    public function listAdmin(){

    }




    public function listAdmin(){

    }

    public function addAdmin(){

    }

    public function deleteAdmin(){

    }
}