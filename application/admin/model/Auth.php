<?php
namespace app\admin\model;

use think\Model;
class Auth extends Model
{
	public function authname()
	{
		$authname = $this->select();
		return $authname;
	}
}