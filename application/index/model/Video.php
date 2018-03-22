<?php

namespace app\index\model;
use think\Model;
use think\Controller;
use think\Db;
use traits\model\SoftDelete;
class Video extends Model
{
	
	public function selcat()
	{
		return Db::name('category')->where('parentid','<>',0)->select();
	}
	//轮播图查询
	public function selvid1()
	{
		return Db::name('video')->where('lunbo','=',1)->select();
	}


   /* public function tiezi()
    {
        return Db::name('tiezi')->select();
    }*/
	//6个视频查询
	/*public function selvid6()
	{
		return Db::name('video')->limit(6)->select();
	}*/
	/*public function insuser($title,$fenqu,$vimg,$content,$vpath)
	{

		return Db::table('think_video')->insert(['title'=>$title, 'fenqu'=>$fenqu, 'vimg'=>$vimg, 'content'=>$content, 'vpath'=>$vpath]);
	}*/
	function videoAll()
    {
        return Db::name('video')->select();
    }

    //视频查询
    function videoLimit($limit)
    {
        return Db::name('video')->limit($limit)->select();
    }
    //视频条件查询
    function order($limit,$order)
    {
        return Db::name('video')->limit($limit)->order("$order desc")->select();
    }
    //所有视频查询
    function allvideo()
    {
        return Db::name('video')->select();
    }
    //视频条件查询所有
    function orderall($order)
    {
        return Db::name('video')->order("$order desc")->select();
    }
}