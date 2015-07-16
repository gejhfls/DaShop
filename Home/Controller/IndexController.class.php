<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\GoodsModel;
class IndexController extends Controller {
    public function index(){
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }

    public function home(){
        $page['current']=1;//默认第一页
        $page['brand']='';
        $page['type']='';
        $page['burl']='';
        $page['turl']='';
        $page['url']='/home';
        $where="'1'='1'";
        $tid=I('get.tid');
        $subtitle=' ';
        $size=3;

        if(isset($_GET['page']))$page['current']=I('page')+0;

        $brand=D('Brand')->select();
        if(isset($_GET['brand'])){
            $page['brand']=I('brand')+0;$page['url'].="/brand/{$page['brand']}";
            $page['turl'].="/brand/{$page['brand']}";
            $where.="and brand_id='{$page['brand']}'";
            foreach($brand as $val){
                if($val['brand_id']==I('brand')){
                    $subtitle.="&{$val['brand_name']}";
                }
            }
        }
        if(isset($_GET['type'])){
            $page['type']=I('type');$page['url'].="/type/{$page['type']}";
            $page['burl'].="/type/{$page['type']}";
            if(I('type')=='new'||I('type')=='New') {
                $where .= "and is_new='1'";
            }
            if(I('type')=='hot'||I('type')=='Hot') {
                $where .= "and is_hot='1'";
            }
        };

        if($tid!=''){
            $where.="and (cat_id='$tid'";//用于查询
            $page['url'].="/tid/$tid";
            $page['burl'].="/tid/$tid";
            $page['turl'].="/tid/$tid";
        }else{
            $where.="and ( '1'='1'";
        }


        $good = M('Goods');
        $title='Clothes';


        $cate= M('Category')->select();
        foreach($cate as $val){
            if($val['cat_id']==$tid) {
                $where.="or cat_id = '{$val['parent_id']}'";
                $title = $val['cat_name'];
            }
            if($val['parent_id']==$tid) {
                $where.="or cat_id = '{$val['cat_id']}'";
            }
        }
        $where.=')';
        load('@.tree');
        $cateArray=array();

        foreach($cate as $key=>$val){

            if($val['parent_id']==0) {
                $val['child'] = getTree($cate, $val['cat_id']);
                $cateArray[] = $val;
            }
        }
//        echo $where;
//       根据参数筛选商品并分页
        $count=$good->where($where)->count();

        $page['current']=$page['current']<$count/$size?$page['current']:ceil($count/$size);
        $page['current']=$page['current']>0?$page['current']:1;
        $result = $good->where($where)->page("{$page['current']},$size")->select();
        $page['last']=$page['current']-1>0?$page['current']-1:1;
        $page['next']=$page['current']<($count)/$size?$page['current']+1:$page['current'];


//        var_dump($cateArray);exit;
        $this->assign("category",$cateArray);
        $this->assign("welcome","hello world");
        $this->assign('brand',$brand);
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $heading = $this->fetch("Public/heading");
        $this->assign("result",$result);
        $this->assign("heading",$heading);
        $this->assign('subtitle',$subtitle);
        $this->assign("title",$title);
        $this->assign('page',$page);

        $this->display();
    }

    public function search(){
        $search=I('get.search');
        $page['current']=1;
        $size=5;
        if($search==''){
            $this->redirect('home');
            exit;
        }
        if(isset($_GET['page']))$page['current']=I('page')+0;
        $page['url']="/search/search/$search";
        $good = M('Goods');
        $title=$search;


        $cate= M('Category')->select();

        load('@.tree');
        $cateArray=array();

        foreach($cate as $key=>$val){

            if($val['parent_id']==0) {
                $val['child'] = getTree($cate, $val['cat_id']);
                $cateArray[] = $val;
            }
        }
        $where="goods_name like '%$search%' or goods_brief like '%$search%' or goods_brief like '%$search%' or keywords like '%$search%'";
//        echo $where;
//       根据参数筛选商品并分页
        $count=$good->where($where)->count();

        $page['current']=$page['current']<$count/$size?$page['current']:ceil($count/$size);
        $page['current']=$page['current']>0?$page['current']:1;
        $result = $good->where($where)->page("{$page['current']},$size")->select();
        $page['last']=$page['current']-1>0?$page['current']-1:1;
        $page['next']=$page['current']<($count)/$size?$page['current']+1:$page['current'];
        $brand=D('Brand')->select();
//        var_dump($cateArray);exit;
        $this->assign("category",$cateArray);
        $this->assign("welcome","hello world");
        $this->assign('brand',$brand);
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $heading = $this->fetch("Public/heading");
        $this->assign("result",$result);
        $this->assign("heading",$heading);
        $this->assign("title",$title);
        $this->assign('page',$page);

        $this->display('home');
    }


}