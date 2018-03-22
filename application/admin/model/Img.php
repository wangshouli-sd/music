<?php 

namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;

class Img extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $autoWriteTimestamp = true;

	// 列表分页
	public function listpage ()
	{
		return $this->withTrashed()->order('update_time', 'desc')->select();

	}

}