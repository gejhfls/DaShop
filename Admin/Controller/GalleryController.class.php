<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/5
 * Time: 下午2:02
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
use Think\Upload;

class GalleryController extends Controller{
    public function add(){
        if(isset($_GET['id'])) {
            $data = D('Goods')->find($_GET['id']);
            $this->assign('goods', $data);
            $this->display();
        }else{
            $this->error('请重新选择正确的商品',lst);
        }
    }

    public function addOK(){
        $upload=new Upload();
        $upload->autoSub = true;
        $upload->subName = array('date','Ymd');
        // 设置子目录的命名
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  = './Public/';
        $upload->savePath ='./UpLoad/Gallery/';
        $info=$upload->upload ();
        if ($info) {
            // 如果上传成功，获取上传的文件相关信息
            $num=0;
            foreach($info as $key=>$file){
                $furl=$file['savepath'].$file['savename'];
                $turl=$file['savepath'].substr($file['savename'],0,strlen($file['savename'])-4).'tb.jpg';

                $furl='/Public/'.substr($furl,2);
                $turl='/Public/'.substr($turl,2);
                $murl='/Public/'.substr($murl,2);
                $image = new \Think\Image();
                $image->open('.'.$furl);
                $num++;
//                echo $turl;exit();
                $scale=$image->width()/$image->height();
                $image->thumb(350, 350/$scale)->save('.'.$turl);
//                rename("temp.jpg",'.'.$turl);   //顾耳机你他妈的基础那么差连个路径的错误都找不出来还怪框架，真该反省下了


                //写入数据库
                $gallerydata=array();
                $gallerydata['goods_id']=$_POST['goods_id'];
                $gallerydata['img_url']=$furl;
                $gallerydata['thumb_url']=$turl;
                D('Gallery')->add($gallerydata);

            }
            echo "图片上传成功";
        } else {
            // 如果上传失败，提示相关失败原因
            $msg=$upload->getError();
            echo $msg;
        }
    }

    public function lst(){
        $goods = D ( 'Goods' );
        $count = $goods->count ();
        $page = new Page( $count, 5 );
        $show = $page->show ();
        $data = $goods->limit ( $page->firstRow . ',' . $page->listRows )->select ();
        $this->assign ( 'show', $show );
        $this->assign ( 'data', $data );
        $this->display();
    }

    public function admin(){

        if(isset($_GET['id'])) {
            $id=$_GET['id'];
            $data=D('Gallery')->where("goods_id=$id")->select();
            $this->assign('gid',$id);
            $this->assign('data',$data);
            $this->display();
        }else {
            $this->error('请重新选择正确的商品', lst);
        }

    }

    public function changeVal()
    {
//        var_dump($_POST);exit;
        $id=I('img_id');
        $data['img_order']=I('img_order');
        $where="img_id='".$id."'";
//        echo $where;var_dump($data);
        $pic = D('Gallery');
        if($pic->where($where)->save($data)){
            echo 'success';exit;
        }

        echo $pic->getError();
    }

    public function showInPage(){
        $id=I('img_id');
        $where="img_id='".$id."'";
        $goodsId=I('goods_id');
        $pic = D('Gallery');
        $val=$pic->where("goods_id='$goodsId'")->max('img_order');
        $val= intval($val)+1;
        $data['img_order']=$val;
        if($pic->where($where)->save($data)){
            echo $val;exit;
        }
        echo -1;
    }
}