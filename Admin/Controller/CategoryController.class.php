<?php
namespace Admin\Controller;
use Admin\Model\CategoryModel;
use Think\Controller;
class CategoryController extends Controller
{
    /*
     * 创建显示一级分类
     */
    public function createFirst()
    {
        $parent_id = $_POST['parent_id'];
        $data = D('Category')->where("parent_id=$parent_id")->select();
        $this->ajaxReturn($data, '', 1);
    }

    /**
     * 显示添加分类界面
     */
    public function add()
    {
        // 快速实例化用户自定义模型
        $cate = D('Category');
        // 查询所有一级分类信息，并保存到数组data中
//        $data = $cate->where('parent_id=0')->select();
        $data=$cate->select();
        load('@.tree');
        // 生成无限级分类
        $data = makeTree($data,2);
        // 将data分配给模板中
        $this->assign('data', $data);
        // 显示模板
        $this->display('add');
    }

    /**
     * 添加分类信息
     */
    public function addOk()
    {
        // 判断是否点击submit
        if (isset ($_POST ['submit'])) {
            // 实例化自定义模型
            $cate = D('Category');
            // 自动创建

            if($cate->create()){
                // 录入数据
                if ($cate->add()) {
                    $this->success('录入成功', 'add');
                } else {
                    $this->error('录入失败', 'add');

                }
            }else{
                $this->error($cate->getError());
            }


        }

    }
    /**
     * 显示管理分类页面
     */

    /*
     * 关联查询
     */
    public function admin()
    {
        // 实例化对象
        $cate = D('Category');
        // 关联查询
        $data = $cate->relation('cate')->select();
//        var_dump($data);
        // 加载函数文件
        load('@.tree');
        // 生成无限级分类
        $data = getTree($data);

        // 为模板赋值
        $this->assign('data', $data);
        // 显示模板
        $this->display('admin');
    }

    public function deleteAll()
    {
        // 判断是否点击删除
        if (isset ($_POST ['deleteSubmit'])) {
            // 获取删除的数组id
            $id = $_POST ['id'];
            for ($i = 0; $i < count($id); $i++) {

                D('Goods')->where("cat_id={$id [$i]}")->save(array('is_delete'=>1)); //tp中使用关联无法批量删除

                D('Category')->delete($id [$i]); //tp中使用关联无法批量删除
            }
            $this->success('操作成功', 'admin');
        }
    }

    /**
     * 显示修改页面
     */
    public function edit()
    {
        // 要修改的id
        $id = $_GET ['id'];
        // 要修改的数据保存到数组row中
        $row = D('Category')->find($id);
        // 查询所有一级分类信息保存到数组data中
//        $data = D('Category')->where('parent_id=0')->select();
        $data=D('Category')->select();
        load('@.tree');
        // 生成无限级分类
        $data = makeTree($data,2);
        // 为模板分配数据
        $this->assign('row', $row);
        $this->assign('data', $data);

        $this->display('edit');
    }

    /**
     * 修改功能
     */
    public function editOk()
    {
        if (isset ($_POST ['submit'])) {
            $cate = D("Category");
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



}