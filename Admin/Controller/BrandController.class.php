<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/4
 * Time: 上午8:52
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

class BrandController extends Controller{
    public function add(){
        $this->display();
    }

    public function addOk(){
        if (isset ( $_POST ['submit'] )) {

            if(empty($_FILES['logo_file']['tmp_name'])) {
                $this->error('请选择logo图片', 'add');
                exit();
            }
            // 实例化上传类对象
            $upload = new Upload();
            // 设置文件名的规则
            $upload->autoSub = true;
            $upload->subName = array('date','Ymd');
            // 设置子目录的命名
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            // 设置允许上传文件的后缀名
            // 设置保存路径

            $upload->rootPath  = './Public/';
            $upload->savePath ='./UpLoad/Brand/';

//            $upload->rootPath  ='./Common/';
            // 判断是否上传成功
            $info=$upload->upload ();
            if ($info) {
                // 如果上传成功，获取上传的文件相关信息
//                var_dump($info);
            } else {
                // 如果上传失败，提示相关失败原因
                $this->error ( $upload->getError(), 'add' );
                exit ();
            }
            // 上传成功的文件名称
            foreach($info as $file){
                $furl=$file['savepath'].$file['savename'];
                $furl='/Public/'.substr($furl,2);
                $_POST['brand_logo']=$furl;
            }
//            echo $_POST['photo'];
            // 实例化模型
            $goods = D ( 'Brand' );
            // 自动创建
            $goods->create ();
//            var_dump($goods);exit();
            // 判断是否录入成功
            if ($goods->add ()) {
                // 成功
                $this->success ( '录入成功', 'add' );
            } else {
                // 失败
                $this->error ( '录入失败', 'add' );
            }
        }
    }
    public function edit()
    {
        // 要修改的id
        $id = $_GET ['id'];
        // 要修改的数据保存到数组row中
        $row = D('Brand')->find($id);
        // 查询所有一级分类信息保存到数组data中
//        $data = D('Category')->where('parent_id=0')->select();
        // 为模板分配数据
        $this->assign('row', $row);

        $this->display('edit');
    }

    public function editOk()
    {
        if (isset ($_POST ['submit'])) {
            $cate = D("Brand");
            if($cate->create()){
                // 录入数据
//                var_dump($cate->data());exit;

                if ($cate->save()) {
                    $this->success('修改成功', 'admin');
                } else {
                    $this->error('修改失败', 'admin');
                }
            }else{
                $this->error($cate->getError());
            }
        }
    }

    public function lst(){
        $this->display();
    }


    public function admin(){
        $brand=D('Brand');
        $data=$brand->select();
        $this->assign('data',$data);
        $this->display();
    }

    public function deleteAll()
    {
        // 判断是否点击删除
        if (isset ($_POST ['deleteSubmit'])) {
            // 获取删除的数组id
            $id = $_POST ['id'];
            for ($i = 0; $i < count($id); $i++) {
                $brand=D('Brand');
                $data['is_show']=0;
//                D('Goods')->where("brand_id={$id [$i]}")->save(array('is_delete'=>1));
                $brand->where("brand_id={$id[$i]}")->field('is_show')->save($data);
            }
            $this->success('操作成功', 'admin');
        }
    }
}