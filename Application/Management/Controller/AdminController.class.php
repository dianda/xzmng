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
    public function addUserPage()
    {
        $area=D('area_xz');
        $resultarea=$area->select();
        $jg=D('jgdm_xz');
        $resultjg=$jg->select();
        $this->assign('area',$resultarea);
        $this->assign('jg',$resultjg);
        $this->display();
    }
    public function addUser()
    {
        $data['SFZJLX']=$_POST['sfzjlx'];
        $data['SFZJH']=$_POST['sfzjh'];
        $data['GH']=$_POST['gh'];
        $data['JGDM']=$_POST['jgdm'];
        $data['EMAIL']=$_POST['email'];
        $data['TEL']=$_POST['tel'];
        $data['XM']=$_POST['xm'];
        $data['AREA']=$_POST['area'];

        $role=M('user_xz');
        if($role->where("SFZJH=".$_POST['sfzjh'])->count()==0)
        {
            if($role->add($data))
            {
                $this->success('新增人员成功！');
            }
            else
            {
                $this->error('新增人员失败！');
            }
        }

    }
    public function deleteUser()
    {
        $role=M('user_xz');
        if($role->where('ID='.$_GET['userid'])->delete())
        {
            $this->success('删除成功！');
        }
        else
        {
            $this->error('删除失败！');
        }

    }
    public function addManager()
    {
        if($_POST['userid']!=''&& $_POST['glylb']!='')
        {
            $data['USERID'] =$_POST['userid'];
            $data['ROLE'] =$_POST['glylb'];
            $role=M('role_xz');
            $count=$role->where("USERID=".$_POST['userid'])->count();
            if($count==0)
            {
                   if($role->add($data))
                   {  echo 'ok'; }
                    else
                    {  echo'error'; }
            }
            else{echo'ycz'; }
        }
        else
        {  echo'error'; }
    }
    public function listManager()
    {
        $pagem=new \Think\Lpage();
        $pagem->pageSize='10';
        $pagem->pageNumber='6';
        $pagem->setTables('user_xz a,jgdm_xz b,area_xz c,role_xz d');

        $pagem->setColumns('a.ID,a.XM,b.NAME as JG,a.TEL,c.NAME as QX,d.ROLE');
        $pagem->setOrders('a.ID desc');
        $pagem->setPrecon('a.JGDM=b.ID&&a.AREA=c.ID&&a.ID=d.USERID');
        $pager = $pagem->pageShow();

        $this->assign('data',$pagem->list);
        $this->assign('page',$pager);
        $this->display();
    }

    public function deleteManager()
    {
        $role=M('role_xz');
       if($role->where('USERID='.$_GET['userid'])->delete())
       {
           $this->success('删除成功！');
       }
       else
        {
            $this->error('删除失败！');
        }

    }


    public function listOrg()
    {
        $area=D('area_xz');
        $resultarea=$area->select();
        $Model = D("jgdm_xz");
        $resultjg=$Model->table('jgdm_xz a,area_xz b')
            ->field('a.NAME as JG,b.NAME QX,a.ID')
            ->order('a.id desc')
            ->where('a.AREAID=b.ID')
            ->select();
        $this->assign('area',$resultarea);
        $this->assign('data',$resultjg);

        $this->display();
    }

    public function deleteOrg()
    {
        $role=M('jgdm_xz');
        if($role->where('ID='.$_GET['id'])->delete())
        {
            $this->success('删除成功！');
        }
        else
        {
            $this->error('删除失败！');
        }

    }
    public function addOrg()
    {
        if($_POST['areaid']!=''&& $_POST['orgname']!='')
        {
            $data['AREAID'] =$_POST['areaid'];
            $data['NAME'] =$_POST['orgname'];
            $role=M('jgdm_xz');
            $count=$role->where("NAME='".$_POST['orgname']."'")->count();
            if($count==0)
            {
                if($role->add($data))
                {   $this->success('添加成功！'); }
                else
                {     $this->error('添加失败！'); }
            }
            else{  $this->error('已存在！');}
        }
        else
        { $this->error('数据不全请检查！'); }

    }





}