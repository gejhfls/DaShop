<?php
$menus = array(
    'goodsmanage'=>array(   //$menus_priv['goodsmanage']
        'goodslist'=>'/Goods/lst',
        'addgoods'=>'/Goods/add',
        'goodstype'=>'/GoodsType/lst'
    ),
    'gallerymanage'=>array(
        'gallerylist'=>'/Gallery/lst',
//        'addgallery'=>'/Gallery/add',
//        'galleryadmin'=>'/Gallery/admin'
    ),
    'brandmanage'=>array(
        'addbrand'=>'/Brand/add',
        'brandlist'=>'/Brand/admin'
    ),
    'categorymanage'=>array(
        'categorylist'=>'/Category/admin',
        'addcategory'=>'/Category/add',
    ),
    'ordermanage'=>array(
        'orderlist'=>'/Order/lst',
    ),


//        'membermanage'=>array(
//    'memberlist'=>'/MemberLevel/lst',
//    ),

);

//定义一个数组，用于设置按钮对应的权限,每个显示的按钮要对应一个权限。
//格式：‘按钮名称’==对应的权限。
$menus_priv=array(
    'goodsmanage'=>array('goodslist','addgoods','goodstype'),
    'goodslist'=>'goodslist',
    'addgoods'=>'addgoods',
    'goodstype'=>'goodstype',
    'categorymanage'=>array('addcategory','categorylist'),
    'addcategory'=>'addcategory',
    'categorylist'=>'categorylist',
    'ordermanage'=>array('orderlist'),
    'orderlist'=>'orderlist',
    'membermanage'=>array('memberlist'),
    'memberlist'=> 'memberlist',
    'brandmanage'=>array('addbrand','brandlist'),
    'addbrand'=>'addbrand',
    'brandlist'=>'brandlist',
    'gallerymanage'=>array('gallerylist', 'addgallery', 'galleryadmin'),
    'gallerylist'=>'gallerylist',
    'addgallery'=>'addgallery',
    'galleryadmin'=>'galleryadmin'

);
return array(
    'menus'=>$menus,
    'menus_priv'=>$menus_priv
);
?>