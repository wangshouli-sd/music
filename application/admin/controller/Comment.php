<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\controller\Auth;
use app\admin\model\Comment as Comm;
use think\Db;
use think\Request;
use lib\Pager;



// 评论管理
// 评论列表
class Comment extends controller 
{
	protected $comment;

	// 初始化控制器
	public function _initialize()
	{
		$this->comment = new Comm;
	}


	// 评论
	public function feedback_list()
	{
		$data = Db::name('tiezi')->select();

		// var_dump($data);
		$count = count($data);

		$this->assign('data', $data);
		$this->assign('count', $count);

		// var_dump($count);
		return $this->fetch();
	}	

	// 评论删除
	public function member_del()
	{
		
		$id = input('get.id');
		// $this->assign('id',$id);
		// $this->assign('status',$status);

		$data = Db::name('tiezi')->where('id', $id)->delete();
		// var_dump($data);
		if($data){
            return json_encode(['state' => 1]);
        }else{
            return json_encode(['state' => 0]);
        }
		return $this->fetch();
	}	

	// 多删
	public function memberdelete()
    {
        $id[] = input('post.data');
        echo  $id;
    }
	// 查看评论

	public function member_show()
	{
		return $this->fetch();
	}
	

}
