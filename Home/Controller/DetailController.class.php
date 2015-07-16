<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/19
 * Time: 下午5:45
 */
namespace Home\Controller;
use Think\Controller;
use Home\Model\GoodsModel;
use Admin\Model\AttributeModel;
class DetailController extends Controller {
    public function index(){
        if(IS_GET) {
            if(empty($_GET['id']))$_GET['id']=2;
            $_GET['id']+=0;
            $cate= M('Category')->select();
            load('@.tree');
            $cateArray=array();
            foreach($cate as $key=>$val){

                if($val['parent_id']==0) {
                    $val['child'] = getTree($cate, $val['cat_id']);
                    $cateArray[] = $val;
                }
            }
//        var_dump($cateArray);exit;
            $this->assign("category",$cateArray);
            $this->assign("welcome","hello world");
            $heading = $this->fetch("Public/heading");
            $this->assign("heading",$heading);
            $goodinfo = M('Goods')->where("id={$_GET['id']}")->find();
            if(!$goodinfo||$goodinfo['cat_id']==''){$this->error('Not Found The Good');exit;}
            $recgoods= M('Goods')->where("cat_id={$goodinfo['cat_id']}")->limit(0,4)->select();
            $pic = M('Gallery')->where("goods_id={$_GET['id']}")->order('img_order desc')->limit(0,5)->select();

            //调用函数获取商品的单选属性信息
            $attrsdata = $this->getattr( $_GET['id']);
            // print_r($attrsdata);exit;
            $this->assign('attrsdata',$attrsdata);
            $lan = $this->getlan();//该数组信息可以通过attribute表计算出来的。
            $this->assign('lan',$lan);
            $this->assign("goods",$goodinfo);
//            var_dump($good);exit;
            $this->assign("pic",$pic);
            $this->assign("recs",$recgoods);

            $this->display();
        }
    }

    public function home(){

        $good = M('Goods');
        $result = $good->select();

        $this->assign("welcome","hello world");
//        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $heading = $this->fetch("Public/heading");
        $this->assign("result",$result);
        $this->assign("heading",$heading);
        $this->display();
    }

    protected  function getattr($goods_id){

        $sql="select a.attr_id,b.attr_name,a.attr_value,a.attr_price   from  goods_attr a left join attribute b on a.attr_id=b.id where a.goods_id=$goods_id and b.attr_type=1";
        $model = D('Goods');
        $data = $model->relation('attr')->find($goods_id);
        $data = $data['attr'];
        $list=array();
        foreach($data as $v){
            $list[$v['attr_id']][]=array(
                'attr_value' =>$v['attr_value'],
                'attr_price' =>$v['attr_price'],
            );
        }
//        var_dump($list);exit;
        return $list;
    }
    //构建一个数组，在属性的id对应属性的名称的一个数组
    protected  function getlan(){
        $attr = D('Attribute');
        $data = $attr->select();
        $list=array();
        foreach($data as  $v){
            $list[$v['id']]=$v['attr_name'];
        }
        return $list;
    }


}