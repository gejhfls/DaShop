<?php

// 无限级分类,生成树状结构
function getTree($data, $cid = 0) {
	static $tree = array ();
	foreach ( $data as $key => $value ) {
		if ($value ['parent_id'] == $cid) {
			$tree [] = $value;
			unset ( $data [$key] );
			getTree ( $data, $value ['cat_id'] );
		}
	}
	return $tree;
}

function makeTree($data,$stop=100, $cid = 0,$level=1) {
    static $tree = array ();
    foreach ( $data as $key => $value ) {
        if (($value ['parent_id'] == $cid)&&$stop>$level) {
            for($ii=0;$ii<$level;$ii++) {
                $value['cat_name'] = '&nbsp;&nbsp;' . $value['cat_name'];
//                $value['cat_name'] = '*' . $value['cat_name'];
            }
            $tree [] = $value;
            unset ( $data [$key] );
            makeTree ( $data,$stop,$value ['cat_id'],$level+1 );
        }
    }
    return $tree;
}
?>