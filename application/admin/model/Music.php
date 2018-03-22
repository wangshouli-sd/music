<?php

// namespace app\admin\model;

// use think\Model;
// use traits\model\SoftDelete;
// use app\admin\model\Musicinfo;

namespace app\admin\model;
use think\Model;
use think\Controller;
use think\Db;
use traits\model\SoftDelete;
class Video extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}


























// class Music extends Model
// {
// 	protected $autoWriteTimestamp = true;
// 	use SoftDelete;
// 	protected $deleteTime = 'delete_time';
// 	protected $musicinfo;
// 	// 初始化控制器
// 	public function _initialize()
// 	{
// 		$this->musicinfo = new Musicinfo;
// 	}

// 	public function info()
// 	{
// 		$arr = [];
// 		$data = $this->withTrashed()->select();
// 		foreach ($data as $key => $value) {
// 			$arr[$key]['vid'] = $value->vid;
// 			$arr[$key]['author'] = $value->author_id;
// 			$arr[$key]['tag'] = $value->tag_id;
// 			$arr[$key]['bkname'] = $value->bkname;
// 			if (!is_null($value->image)) {
// 				$arr[$key]['image'] = $value->image;
// 			} else {
// 				$arr[$key]['image'] = '\upload\demo.jpg';
// 			}
			
// 			$arr[$key]['create_time'] = $value->create_time;
// 			$arr[$key]['delete_time'] = $value->delete_time;
// 		}
// 		return $arr;
// 	}


// 	// public function xiajia($id)
// 	// {
// 	// 	$re = $this->destroy($id);
// 	// 	if($re){
// 	// 		$info = ['code'=>1];
// 	// 		echo json_encode($info);
// 	// 	}else{
// 	// 		$info = ['code'=>0];
// 	// 		echo json_encode($info);
// 	// 	}
// 	// }
// 	// public function fabu($aid)
// 	// {
// 	// 	$re = $this->save(['delete_time'=>NULL],['id'=>$aid]);
// 	// 	if($re){
// 	// 		$info = ['code'=>1];
// 	// 		echo json_encode($info);
// 	// 	}else{
// 	// 		$info = ['code'=>0];
// 	// 		echo json_encode($info);
// 	// 	}
// 	// }
// 	//  删除音乐
// 	// public function delmusic($id)
// 	// {
// 	// 	$re = $this->destroy($id,true);
// 	// 	if($re){
// 	// 		$info = ['code'=>1];
// 	// 		echo json_encode($info);
// 	// 	}else{
// 	// 		$info = ['code'=>0];
// 	// 		echo json_encode($info);
// 	// 	}
// 	// }

// }

