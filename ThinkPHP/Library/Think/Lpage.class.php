<?php
namespace Think;
class Lpage {
    private $total;			//总数量
    private $limit;			//返回mysql的limit语句
    private $pageStart;		//开始的数值
    private $pageStop;		//结束的数值
    private $pageNumber;	//显示分页数字的数量
    private $page;			//当前页
    private $pageSize;		//每页显示的数量
    private $pageToatl;		//分页的总数量
    private $pageParam;		//分页变量

    private $table;			//单表表名
    private $order;			//单表排序规则
    private $precon;	    //单表前置条件

    private $field;         //单表条件字段数组
    private $operation;		//单表条件连接符数组
    private $condition;		//单表条件值数组

    private $columns;		//多表查询的字段
    private $tables;		//多表查询表以及别名
    private $orders;		//多表查询排序条件
    private $conditions;	//多表连接规则/条件

    private $list;          //返回的数据
    /**
     * 分页设置样式 不区分大小写
     * %pageToatl%  //总页数
     * %page%		//当前页
     * %pageSize% 	//当前页显示数据条数
     * %pageStart%	//本页起始条数
     * %pageStop%	//本页结束条数
     * %total%		//总数据条数
     * %first%		//首页
     * %ending%		//尾页
     * %up%			//上一页
     * %down%		//下一页
     * %F%			//起始页
     * %E%			//结束页
     * %pagenumber%	//固定数量的数字分页
     * %input%		//跳转输入框
     * %GoTo%		//跳转按钮
     */
    private $pageType = '<div style="float:left">%page%%total%%pageToatl%%numperpage%</div><div style="float:right">%first%%up%%down%%ending%%input%%GoTo%</div>';
    //$page->pageType = '%total%%pageToatl%%numperpage%%first%%up%%pagenumber%%down%%ending%%input%%GoTo%';
    //显示值设置
    private $pageShow = array('first'=>'首页','ending'=>'末页','up'=>'上一页','down'=>'下一页','GoTo'=>'GO');

    function __construct()
    {
        $this->precon ='';
        $this->pageSize='10';
        $this->pageNumber='5';
    }

    /**
     * 显示分页
     * @access public
     * @return string HTML分页字符串
     */
    public function pageShow(){
        $this->calculate();
        if($this->pageToatl>1){
            if($this->page == 1){
                $first = '<a style="color:#ccc">'.$this->pageShow['first'].'</a>';
                $up = '<a style="color:#ccc">'.$this->pageShow['up'].'</a>';

            }else{
                $first = '<a>'.$this->pageShow['first'].'</a>';
                $up = '<a>'.$this->pageShow['up'].'</a>';
            }
            if($this->page >= $this->pageToatl){
                $ending = '<a style="color:#ccc">'.$this->pageShow['ending'].'</a>';
                $down = '<a style="color:#ccc">'.$this->pageShow['down'].'</a>';

            }else{
                $ending = '<a>'.$this->pageShow['ending'].'</a>';
                $down = '<a>'.$this->pageShow['down'].'</a>';
            }
            $input  = ' 跳转到 '.'<input type="text" value="'.$this->page.'" onkeydown="javascript: if(event.keyCode==13){var page=parseInt(this.value); hrefgo(page); return false;}">';
            $GoTo = '<button onclick="javascript:var page=parseInt(this.parentNode.previousSibling.firstElementChild.value); hrefgo(page); return false;">'.$this->pageShow['GoTo'].'</button>';
        }else{
            $first = '';$up ='';$ending = '';$down = '';$input = '';$GoTo = '';
        }
        $this->pagenumber();
        return "<div id='pageA'>".$this->css().str_ireplace(array('%numperpage%','%pageToatl%','%page%','%pageSize%','%pageStart%','%pageStop%','%total%','%first%','%ending%','%up%','%down%','%input%','%GoTo%'),array('<span class="numperpage">每页显示: '.$this->pageSize.'</span>','<span class="pageToatl">总页数: '.$this->pageToatl.'</span>','<span class="thispage">当前页: '.$this->page.'</span>',$this->pageStop-$this->pageStart,$this->pageStart,$this->pageStop,"<span class='pagedatatotal'>总记录数: ".$this->total.'</span>',"<span class='firstpage'>".$first.'</span>',"<span class='endpage'>".$ending.'</span>',"<span class='pageup'>".$up.'</span>','<span class="pagedown">'.$down.'</span>','<span class="pageinput">'.$input.'</span>','<span class="goto">'.$GoTo.'</span>'),$this->pageType).$this->js()."</div>";
    }

    /**
     *	数字分页
     */
    private function pagenumber(){
        $pageF = stripos($this->pageType,'%pagenumber%');
        $pagenumber = '';$numberD = '';$F = '';$E ='';$omitF = '';$omitFA = '';$omitE = '';$omitEA = '';
        if($pageF!==false){
            if($pageF!==false){
                $number = $this->pageNumber%2==0?$this->pageNumber/2:($this->pageNumber+1)/2;
                $DStart = $this->page - $number<0?$this->page - $number-1:0;
                if($this->page+$number-$DStart > $this->pageToatl){
                    $DStop = ($this->page+$number-$DStart) - $this->pageToatl;
                    $forStop = $this->pageToatl+1;
                }else{
                    $DStop = 0;
                    $forStop = $this->page+$number-$DStart+1;
                }
                $forStart = $this->page-$number-$DStop<1?1:$this->page-$number-$DStop;
                for($i=$forStart;$i<$forStop;++$i){
                    if($i==$this->page){
                        $pagenumber .= '<span class="current">'.$i.'</span>';
                    }else{
                        $pagenumber .= '<a>'.$i.'</a>';
                    }
                }
            }

            $F = $forStart>1?'<a>1</a>':'';
            $E = $forStop<$this->pageToatl+1?'<a>'.$this->pageToatl.'</a>':'';

        }
        $this->pageType = str_ireplace(array('%F%','%E%','%omitFA%','%omitEA%','%omitF%','%omitE%','%pagenumber%'),array($F,$E,$omitFA,$omitEA,$omitF,$omitE,'<span class="number">'.$pagenumber.'</span>'),$this->pageType);
    }


    /**
     * 设置条件字段
     */
    public function setField($key,$value)
    {
        $this->field[$key]=$value;
    }
    /**
     * 设置条件连接符
     */
    public function setOperation($key,$value)
    {
        $this->operation[$key]=$value;
    }
    /**
     * 设置条件的值
     */
    public function setCondition($key,$value)
    {
        $this->condition[$key]=$value;
    }


    /**
     * 设置多表值
     */
    public function setTables($value)
    {
        $this->tables=$value;
    }

    /**
     * 设置多表查询字段
     */
    public function setColumns($value)
    {
        $this->columns=$value;
    }

    /**
     * 设置多表查询条件
     */
/*    public function setConditions($value)
    {
        $this->conditions=$value;
    }*/

    /**
     * 设置多表排序条件
     */
    public function setOrders($value)
    {
        $this->orders=$value;
    }




    /**
     * 合成分页条件
     */
    public function composeCondition()
    {

        $arr=array();
        if(!empty($this->precon))
        {
            array_push($arr,"(".$this->precon.")");
        }
        foreach ($this->field as $k => $v)
        {
            if (!(empty($this->condition[$k])||empty($this->operation[$k])||empty($this->operation[$k])))
            {
                if(trim($this->operation[$k])=='like')
                {
                    array_push($arr, "(".$this->field[$k]." ".$this->operation[$k]." '%".$this->condition[$k]."%')");
                }
                else
                {
                    array_push($arr,"(".$this->field[$k]." ".$this->operation[$k]." ".$this->condition[$k].")");
                }
            }
        }

        return implode('&&',$arr);
    }

    /**
     * 设置limit方法及计算起始条数和结束条数
     */
    private function calculate()
    {
        $this->pageParam ='condition0';
        if(!empty($this->tables))
        {
            $first = explode(" ",$this->tables);
            $D = D($first[0]);
            $this->total = $D->table($this->tables)->field($this->columns)->order($this->orders)->where($this->composeCondition())->count();
            $this->pageSize = $this->pageSize<0?0:$this->pageSize;
            $this->pageNumber = $this->pageNumber<0?0:$this->pageNumber;
            $this->pageToatl = ceil($this->total/$this->pageSize);
            $this->page = intval($_POST[$this->pageParam]);
            $this->page=empty($this->page)?1:$this->page;
            $this->page = $this->page>=1?$this->page>$this->pageToatl?$this->pageToatl:$this->page:1;
            $this->list=$D->table($this->tables)->field($this->columns)->order($this->orders)->limit(($this->page-1)*$this->pageSize.','.$this->pageSize)->where($this->composeCondition())->select();
            //echo $D->getlastsql();
        }
        else
        {
            $this->total = M($this->table)->where($this->composeCondition())->count();
            $this->pageSize = $this->pageSize<0?0:$this->pageSize;
            $this->pageNumber = $this->pageNumber<0?0:$this->pageNumber;
            $this->pageToatl = ceil($this->total/$this->pageSize);
            $this->page = intval($_POST[$this->pageParam]);
            $M=M($this->table);
            $this->page=empty($this->page)?1:$this->page;
            $this->page = $this->page>=1?$this->page>$this->pageToatl?$this->pageToatl:$this->page:1;
            $this->list=$M->where($this->composeCondition())->order('id')->limit(($this->page-1)*$this->pageSize.','.$this->pageSize)->select();
        }
        //echo $M->getlastsql();
        $this->pageStart = ($this->page-1)*$this->pageSize;
        $this->pageStop = $this->pageStart+$this->pageSize;
        $this->pageStop = $this->pageStop>$this->total?$this->total:$this->pageStop;
        $this->limit = $this->pageStart.','.$this->pageStop;
    }

    /**
     * 设置过滤器
     */
    public function __set($name,$value){
        switch($name){
            case 'pageType':
            case 'uri':
            case 'pageSize':
            case 'where':
            case 'pageNumber':
            case 'order':
            case 'table':
                $this->$name = $value;
                return;
            case 'pageShow':
                if(is_array($value)){
                    $this->pageShow = array_merge($this->pageShow,$value);
                }
                return;
            default:
                return;
        }
    }

    /**
     * 取值过滤器
     */
    public function __get($name)
    {
        switch($name){
            case 'limit':
            case 'pageStart':
            case 'pageStop':
            case 'list':
                return $this->$name;
            default:
                return ;
        }
    }


    /**
     * 前台JS
     */
    public function css()
    {
        $css="
<style type='text/css'>
#pageA{height:60px;color:#5f5f5f;font-size: 12px;width:100%}
#pageA a{text-decoration: none;color:#358AFC;}
#pageA .disabled{display:none;}
#pageA a{display:block;float:left;padding:0 6px;height:24px;line-height:24px;border:1px solid #53A3FD;margin-left:3px;margin-top:33px;-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius:3px;}
#pageA .number{display:block;float:left;padding:0px;}
#pageA .number a{padding:0px;height:24px;width:30px;line-height:24px;text-align:center;}
#pageA 	span.current{margin-left:3px;padding:34px 0 0;display:block;float:left;height:24px;width:30px;line-height:24px;text-align:center;}
#pageA  .pageinput{margin-left:5px;float:left;padding-top:35px;}
#pageA  .pageinput input{height: 18px;width:50px;-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius:3px;border:1px solid #D4D4D4;padding: 2px 10px;}
#pageA  .goto{margin-left:5px;float:left;padding-top:35px;}
#pageA  .goto button{height: 24px;}
#pageA  .pagedatatotal,.pageToatl,.numperpage,.thispage{margin-top: 33px;float: left;margin-right: 5px;height: 24px;line-height: 24px;border: 1px solid #ccc;padding: 0px 5px;color:#6B6969;}
#pageA button{background-color:#58A5FC; color:#fff;width:50px;  border-width:0px; -moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;}
</style>
";
        return $css;
    }

    /**
     * 前台JS
     */
    public function js()
    {



        foreach ( $this->condition as $key => $value ) {
            $condition[$key] = urlencode ( $value );
        }


        $js="<script type='text/javascript'>
//改变select
function changItem(objSelect,objItemText) {  
    for(var i=0;i<objSelect.options.length;i++) {  
        if(objSelect.options[i].value == objItemText) {  
            objSelect.options[i].selected = true;  
            break;  
        }  
    }  
}
//js  模仿jquery DOM readey
(function () {
  var ie = !!(window.attachEvent && !window.opera);
  var wk = /webkit\/(\d+)/i.test(navigator.userAgent) && (RegExp.$1 < 525);
  var fn = [];
  var run = function () { for (var i = 0; i < fn.length; i++) fn[i](); };
  var d = document;
  d.ready = function (f) {
    if (!ie && !wk && d.addEventListener)
      return d.addEventListener('DOMContentLoaded', f, false);
    if (fn.push(f) > 1) return;
    if (ie)
      (function () {
        try { d.documentElement.doScroll('left'); run(); }
        catch (err) { setTimeout(arguments.callee, 0); }
      })();
    else if (wk)
      var t = setInterval(function () {
        if (/^(loaded|complete)$/.test(d.readyState))
          clearInterval(t), run();
      }, 0);
  };
})();
//保持选择条件
document.ready(function(){
var objarr = new Array();
var objs = document.getElementsByTagName('*');
for(var i=0;i<objs.length;i++)
{
   if(objs[i].getAttribute('name')){
      objarr.push(objs[i]);
   }
}
objarr.forEach(function(e){
	var name=e.name;
	var cond=".urldecode(json_encode($condition)).";
	var cond = eval(cond);
	if (name.substr(0 ,9) == 'condition'){
		if(document.getElementsByName(name)[0].nodeName=='SELECT')
	{
		changItem(document.getElementsByName(name)[0],cond[name.substr(9,name.length)]);
	}
	else if(document.getElementsByName(name)[0].nodeName=='INPUT')
	{
		document.getElementsByName(name)[0].value=cond[name.substr(9,name.length)];	
	}
	}
});
});
//改变DOM
document.ready(function(){
var submit = document.getElementById('pagesubmit');
submit.setAttribute('onclick','hrefgo(1)');

var obj = document.getElementsByTagName('span');
for(var i=0;i<obj.length;i++)
{
if(obj[i].className == 'number')
{
var ObjA = obj[i];
}
else if(obj[i].className == 'pageup')
{
var ObjB = obj[i];
}
else if(obj[i].className == 'pagedown')
{
var ObjC = obj[i];
}
else if(obj[i].className == 'firstpage')
{
var ObjD = obj[i];
}
else if(obj[i].className == 'endpage')
{
var ObjE = obj[i];
}
else if(obj[i].className == 'current')
{
	var current = obj[i];
}

}
if(ObjA){
var jds=ObjA.children.length;
for (var i = 0; i < jds; i++) 
{
	if(ObjA.children[i].nodeName=='A')
	{
	ObjA.children[i].setAttribute('href','javascript:void(0)');
	ObjA.children[i].setAttribute('onclick','hrefgo('+ObjA.children[i].innerHTML+')');
	}
}
}

if(ObjB.children[0].nodeName=='A')
{
	ObjB.children[0].setAttribute('href','javascript:void(0)');
	if(".$this->page."!='1')
	{
		var num = parseFloat(".$this->page.");
		var num = num - 1;
		ObjB.children[0].setAttribute('onclick','hrefgo('+num+')');
	}
}

if(ObjC.children[0].nodeName=='A')
{
	ObjC.children[0].setAttribute('href','javascript:void(0)');
	if(".$this->page."!='".$this->pageToatl."')
	{
		var num = parseFloat(".$this->page.");
		var num = num + 1;
		ObjC.children[0].setAttribute('onclick','hrefgo('+num+')');
	}
}

if(ObjD.children[0].nodeName=='A')
{
	ObjD.children[0].setAttribute('href','javascript:void(0)');
	ObjD.children[0].setAttribute('onclick','hrefgo(1)');

}

if(ObjE.children[0].nodeName=='A')
{
	ObjE.children[0].setAttribute('href','javascript:void(0)');
	ObjE.children[0].setAttribute('onclick','hrefgo('+".$this->pageToatl."+')');

}

});
//生成form数据并提交
function hrefgo(page) 
{  
var myForm = document.createElement('form'); 
myForm.method='post' ; 
myForm.action = window.location.pathname ; 
var myInput0 = document.createElement('input') ; 
myInput0.setAttribute('name', 'condition0') ; 
myInput0.setAttribute('value', page); 
myForm.appendChild(myInput0) ;

var objarr = new Array();
var objs = document.getElementsByTagName('*');
for(var i=0;i<objs.length;i++)
{
   if(objs[i].getAttribute('name')){
      objarr.push(objs[i]);
   }
}
var num=1;
objarr.forEach(function(e){
	var name=e.name;
	if (name.substr(0 ,9) == 'condition')
	{
		 var varname = 'myInput'+num; 
		 window[varname]= document.createElement('input') ; 
		 window[varname].setAttribute('name', name) ; 
		 window[varname].setAttribute('value',document.getElementsByName(name)[0].value); 
		 myForm.appendChild(window[varname]) ; 
	}
	num++;
});
myForm.style.display='none';
document.body.appendChild(myForm) ; 
myForm.submit() ; 
}

//alert(document.getElementsByName('current')[0]);
</script>";
        return $js;

    }














}