<?php

namespace app\admin\model;
use think\Model;

class Tag extends Model
{

	public function classify()
	{
		$arr = [];
		$re = $this->select();
		foreach ($re as $key => $value) {
			$arr[$key]['cid'] = $value->cid;
			$arr[$key]['parentid'] = $value->parentid;
			$arr[$key]['bkname'] = $value->bkname;

		}
		return $arr;
	}

	
	public function getTree($parentid)
	{
		$arr = [];
		$re = $this->select();
		foreach ($re as $key => $value) {
			$arr[$key]['id'] = $value->id;
			$arr[$key]['parentid'] = $value->parentid;
			$arr[$key]['tagname'] = $value->tagname;			
		}
		$tree = [];
		foreach($arr as $key=>$val) {
	        if(is_array($parentid)) {
	        	// $str = implode(',', $pid);
	        	for ($i=0; $i < count($parentid); $i++) { 
	        		if($val['parentid'] == $parentid[$i]){
	        			$pName = $this->where('id',$parentid[$i])->find();
	            		$tree[$key]['pname'] = $pName;
	            		$tree[$key]['name'] = $val;
	            		$this->GetTree($arr , $val['id']);
	        		}
	        	}
	           
	        }
	    }
		return $tree;
	}
		// $arr = [];
		// $re = $this->select();
		// foreach ($re as $key => $value) {
		// 	$arr[$key]['cid'] = $value->cid;
		// 	$arr[$key]['parentid'] = $value->parentid;
		// 	$arr[$key]['bkname'] = $value->bkname;
		// }
		// $tree = [];

		// foreach ($arr as $key => $val) {
		// 	if (is_array($parentid)) {
		// 		if ($i = -; $i < count($parentid); $i++) {
		// 			$
		// 		}
		// 	}
		// }
	public function category($value,$id)
	{
		$re = $this->save(['tagname'=>$value],['id'=>$id]);
		if($re){
			$info = ['code'=>1];
			return json_encode($info);
		}else{
			$info = ['code'=>0];
			return json_encode($info);
		}
	}
	public function del($delid)
	{
		$arr = [];
		$data = $this->select();
		foreach ($data as $key => $value) {
			$arr[$key]['id'] = $value->id;
			$arr[$key]['pid'] = $value->pid;
			$arr[$key]['tagname'] = $value->tagname;			
		}
		foreach ($arr as $key => $value) {
			$result = $this->where('id',$delid)->delete(true);
			if ($value['pid'] == $delid) {
				$re = $this->where('pid',$delid)->delete(true);
				$this->del($delid);
			}
		}
		if($result){
			$info = ['code'=>1];
			return json_encode($info);
		}else{
			$info = ['code'=>0];
			return json_encode($info);
		}
		
	}
	public function kind($inquire)
	{
		foreach ($inquire as $key => $value) {
			$re = $this->where('id',$inquire[$key]['tag'])->find();
			if (!is_null($re)) {
				$inquire[$key]['tagname'] = $re->tagname;
			}	
		}
		return $inquire;
	}
}

}