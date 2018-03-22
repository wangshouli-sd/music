<?php

namespace app\index\model;
use think\Model;
use think\Controller;
use think\Db;
use traits\model\SoftDelete;
class Category extends Model
{
	//查询大板块名 首页名
	public function selcat()
	{
		return Db::name('category')->where('parentid','=',0)->select();
	}



	//查询所有版块 首页 下一级
	public function selcat1()
	{
		return Db::name('category')->select();
	}


	//根据大板块id查询小板块名 等于大板块的id 的菜单名
	public function selxiao($cid)
	{
		return Db::name('category')->where('parentid','=',$cid)->select();
	}





	//根据小板块id查询大板块名
	public function selda($cid)
	{
		return Db::name('category')->where('cid','=',$cid)->select();
	}


	
	//查询所有小板块
	public function xiao()
	{
		return Db::name('category')->where('parentid != 0')->select();
	}
	
}