<?php 

namespace app\admin\model;

use think\Model;
use \think\Image;

use traits\model\SoftDelete;

class Author extends Model
{
	public $author;
	use SoftDelete;
	protected $deleteTime = 'delete_time';
	protected $autoWriteTimeStamp = true;

	public function _initialize()
	{
		$this->author = new Author;

	}

public function img(){
	// 获取表单上传文件 例如上传了001.jpg
	$file = request()->file('image');
	// 移动到框架应用根目录/public/uploads/ 目录下
	if (!is_null($file)) {
		$info = $file->validate(['size'=>15678,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');

		if($info){
		// 成功上传后 获取上传信息
		// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
		return '\uploads\\' . $info->getSaveName();
		} else {
		// 上传失败获取错误信息
		echo $file->getError();
		}
	}
}