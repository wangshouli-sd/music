<?php
namespace app\admin\controller;

use think\Controller;
// use think\Db;

class Info extends controller
{
	// 导航列表展示
	public function article()
	{
		return $this->fetch();
	}

	// 导航列表添加
	public function article_add()
	{
		return $this->fetch();
	}
	

}