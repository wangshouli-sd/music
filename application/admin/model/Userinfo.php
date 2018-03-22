<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Userinfo extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $autoWritestamp = true;

	public function allinfo($data) 
	{

		foreach ($data as $key => $value) {
			$re = $this->withTrashed()->where('user_id', $data[$key]['id'])->find();

			if (isset($re->email)) {
				$data[$key]['email'] = $re->email;
				$data[$key]['image'] = $re->image;
				$data[$key]['level'] = $re->level;
				$data[$key]['address'] = $re->address;

			}
		}
		return $data;
	}
	// 删除
	public function del($id)
	{

		$info = $this->where('user_id', $id)->find();
		if (!is_null($info)) {
			$user_id = $info->id;
			$re = $this->destroy($userid);
		}
	}
	// 
	public function active($id)
	{
		$re = $this->save(['delete_time'=>NULL], ['user_id'=>$id]);
	}
	// 删除
	public function realdelete($id)
	{
		$info = $this->withTrashed()->where('user_id', $id)->find();

		if (!is_null($info)) {
			$userid = $info->id;
			$this->destroy($userid, true);
		}
	}
	// 多删
	public function delduo($id)
	{
		return $this->withTrashed()->where('user_id', 'in', $id)->find()->delete();

	}
}
