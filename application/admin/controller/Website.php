<?php
namespace app\admin\controller;

use app\admin\controller\Auth;
use think\Request;

class Website extends Auth
{
	protected $is_login = ['*'];
	public function system_base(Request $request)
	{
		if (!empty($request->param()))
		{

			$str = file_get_contents('../application/config.php');

			foreach($request->param() as $key=>$val)
			{

				$pattern='/\'__' .$key .'__\'=>\'.*?\'/';
	            $replace='\'__' .$key .'__\'=>\''.$val.'\'';
	            // preg_replace — 执行一个正则表达式的搜索和替换
	            $str = preg_replace($pattern, $replace, $str);
	            // dump($str);
			}

			$re = file_put_contents('/application/config.php', $str);

			// dump($re); 

			if ($re) {
				$this->redirect('/Website/system_base');
			}
		}
		// dump($request->param());
		return $this->fetch();
	}

	// 展示系统日志
	public function system_log()
	{
		return $this->fetch();
	}
	
	// 栏目管理
	public function system_category()
	{
		return $this->fetch();
	}
}