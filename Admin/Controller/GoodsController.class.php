<?php
namespace Admin\Controller;
use Admin\Model\GoodsModel;
use Think\Controller;
use Think\Page;
use Think\Upload;

class GoodsController extends Controller {


	/**
	 * 显示商品录入页面
	 */
	public function add() {

		$typedata = D ( 'GoodsType' )->select ();

        $catedata=D('Category')->select();
        load('@.tree');
        // 生成无限级分类
        $catedata = makeTree($catedata);
//        var_dump($catedata);exit();
        $branddata=D('Brand')->select();
        $this->assign ( 'typedata', $typedata );
        $this->assign ( 'branddata', $branddata);
        $this->assign ( 'catedate', $catedata );
		$this->display ( 'add' );

	}

    public function index(){
        $this->add();
    }
	/**
	 * 完成商品信息录入
	 */
	public function addOk() {
        if (isset ( $_POST ['submit'] )) {

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
            $upload->savePath ='./UpLoad/GoodsImg/';

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
            foreach($info as $key=>$file){
                $furl=$file['savepath'].$file['savename'];
                $turl=$file['savepath'].substr($file['savename'],0,strlen($file['savename'])-4).'tb.jpg';
                $furl='/Public/'.substr($furl,2);
                $turl='/Public/'.substr($turl,2);
                $_POST[$key]=$furl;
                $image = new \Think\Image();
                $image->open('.'.$furl);
//                echo $turl;exit();
                $scale= $image->height()/$image->width();
                $image->thumb(300, 300*$scale)->save('.'.$turl);
//                rename("temp.jpg",'.'.$turl);   //顾耳机你他妈的基础那么差连个路径的错误都找不出来还怪框架，真该反省下了
                $key1=str_replace("img","thumb",$key);
                $_POST[$key1]=$turl;

            }
//            echo $_POST['photo'];
            // 实例化模型

            if($_POST['goods_sn']==''){
                $_POST['goods_sn']=rand(1,10000);
            }
            if(isset($_POST['promote_start_time']))
            $_POST['promote_start_date']=strtotime($_POST['promote_start_time']);
            if(isset($_POST['promote_end_time']))
            $_POST['promote_end_date']=strtotime($_POST['promote_end_time']);
            if(isset($_POST['new_last_time']))
            $_POST['new_last_time']=strtotime($_POST['new_last_time']);
            $_POST['add_time']=strtotime("now");
            $goods = D ( 'Goods' );
            // 自动创建
           if(!$goods->create ()){
               $this->error ($goods->getError(), 'add' );exit();
           }
            // 判断是否录入成功

            if ($goodsid=$goods->add ()) {
                $goodsattr=D('GoodsAttr');
                $attr=$_POST['attr'];
                $price=$_POST['price'];

            //遍历属性数据
               foreach($attr as $k=>$v){
                   if(is_array($v)){
                       //如果是单选属性需要遍历各个值
                       foreach($v as $k1=>$v1){
                           $tempdata=array();
                           $tempdata['goods_id']=$goodsid;
                           $tempdata['attr_id']=$k;
                           $tempdata['attr_value']=$v1;
                           $jia = $price[$k][$k1];
                           $tempdata['attr_price']=$jia;
                          if(!$goodsattr->add($tempdata))$this->error ( '部分属性录入失败', 'add' );
                           unset($tempdata);

                       }
                   }else{

                       $tempdata=array();
                       $tempdata['goods_id']=$goodsid;
                       $tempdata['attr_id']=$k;
                       $tempdata['attr_value']=$v;
                       if(!$goodsattr->add($tempdata))$this->error ( '部分属性录入失败', 'add' );
                       unset($tempdata);
                   }
               }

                // 成功
                $this->success ( '录入成功', 'add' );
            } else {
                // 失败
                $this->error ( '录入失败', 'add' );
            }
        }
	}
	
	/**
	 * 显示管理商品页面
	 */
	public function admin() {

		$goods = D ( 'Goods' );
		$count = $goods->count ();
		$page = new Page( $count, 5 );
		$show = $page->show ();
		$data = $goods->relation(true)->limit ( $page->firstRow . ',' . $page->listRows )->select ();

		$this->assign ( 'show', $show );
		$this->assign ( 'data', $data );
		$this->display ( 'admin' );
	}
	
	/**
	 * 显示修改页面
	 */
	public function edit() {
		
		// 查询要修改的商品信息
		$id =I('get.id');
		$goods = D ( 'Goods' );
		$row = $goods->find ( $id );

            $row['promote_start_date']=date('Y-m-d',$row['promote_start_date']);
            $row['promote_end_date']=date('Y-m-d',$row['promote_end_date']);

//        var_dump($row);exit;
		$this->assign ( 'goodinfo', $row );

		$this->display ( 'edit' );
	}
	
	/**
	 * 实现修改功能
	 */
	public function editOk() {
		if (isset ( $_POST ['submit'] )) {
			$goods = D ( 'Goods' );
            $vail=array(
                array('goods_desc','require','请添加商品描述'),
                array('goods_number','number','商品库存必须为数字'),

            );
			if($goods->validate($vail)->create ()){
                if ($goods->save ()) {
                    $this->success ( '操作成功');exit;
                }
            }

            $this->error ($goods->getError());


		}
	}
	
	/**
	 * 实现批量删除
	 */
	public function deleteAll() {
		if (isset ( $_POST ['deleteSubmit'] )) {
			$id = $_POST ['id'];
			$where = implode ( ',', $id );
			if (D ( 'Goods' )->delete ( $where )) {
				$this->success ( '删除成功', 'admin' );
			} else {
				$this->error ( '删除失败', 'admin' );
			}
		}
	}

    public function showattr(){
        $id=$_GET['id']+0;
        $attr=D('Attribute');
        $attrdata=$attr->where("type_id=$id")->select();
        $this->assign('attrdata',$attrdata);
        $this->display('showattr');
    }

    public function lst(){
        $goods = D ( 'Goods' );
        $count = $goods->count ();
        $page = new Page( $count, 10);
        $show = $page->show ();
        $field="id,goods_name,goods_sn,goods_number,shop_price,cat_id,brand_id,is_hot,is_new,is_sale";
        $data = $goods->relation(true)->field($field)->where('is_delete=0')->limit ( $page->firstRow . ',' . $page->listRows )->select ();

//       var_dump($data);exit;
        $this->assign ( 'show', $show );
        $this->assign ( 'data', $data );
        $this->display ( 'lst' );
    }

    public function changeTag(){
        $id= I('post.id');
        $goods = D ( 'Goods' );
        if($goods->field('id','is_sale','is_hot','is_new')->create()){
            if($goods->where("id='$id'")->save()) {
                echo 'success';
                exit;
            }
            echo 'create_error';
        }
            echo $goods->getError();
    }

    public function del(){
        $id= I('post.id');
        $goods = D ( 'Goods' );
        $_POST['is_delete']=1;
        if($goods->field('id','is_delete')->create()){
            if($goods->where("id='$id'")->save()) {
                echo 'success';
                exit;
            }
            echo 'create_error';
        }
        echo $goods->getError();
    }


}