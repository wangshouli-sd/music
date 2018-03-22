<?php

namespace app\admin\model;

use think\Model;
use think\Db;
use traits\model\SoftDelete;


class Comment extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $autoWriteTimestamp = true;

	public function user()
	{
		// 关联对象
		return $this->belongsTo('User');
	}
	// 软删除
	public function del($id)
	{
		$re = $this->destroy($id);

		if ($re) {
			$info = ['code'=>1, 'info'=>'删除成功'];
			echo json_encode($info);
		} else {
			$info = ['code'=>0, 'info'=>'删除失败'];
			echo json_encode($info);
		}
	}	

	 // 还原删除
	public function huanyuan($id)
	{
		$re = $this->save(['delete_time'=>null],['id'=>$id]);
		if($re){
			$info = ['code'=>1,'info'=>'恢复成功'];
			echo json_encode($info);
		}else{
			$info = ['code'=>0,'info'=>'恢复失败'];
			echo json_encode($info);
		}
	}


	// 删除
	public function really($id)
	{
		$re = $this->destroy($id,true);
		// dump($re);
		// return $re;
		if($re){
			$info = ['code'=>1,'info'=>'删除成功'];
			echo json_encode($info);
		}else{
			$info = ['code'=>0,'info'=>'删除失败'];
			echo json_encode($info);
		}
	}
	// 多删
	public function realduo($id)
	{
		$re = $this->destroy($id,true);
		return $re;
	}





}
