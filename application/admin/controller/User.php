<?php
namespace app\admin\controller;

// use app\admin\controller\Auth;
use think\Controller;
use think\Db;
use lib\Pager;
use app\admin\model\User as UserModel;
use think\Request;
use \think\Cookie;
class User extends Controller
{
	// 用户列表
	public function member_list()
	{
		// 查询状态为0的数据用户， 每页显示
		$data = Db::name('user')->paginate(3);
		$page = $data->render();
		// 把分页的数据赋值给list
		$this->assign(['page'=>$page, 'data'=>$data]);
		// 输出模板
		return $this->fetch();
	}
	// 修改用户的页面	
	public function member_edit()
	{	
		$id = $_GET['id'];
		// dump($id);
		$data = Db::name('user')->where('id', $id)->select();
		$this->assign('data', $data);

		return $this->fetch();
	}

	// 修改用户的资料
	public function member_edit1()
	{	
		$id = input('get.id');// id
		$username = input('get.username'); // 用户名
		// $sex = input('get.sex'); // 性别 'sex'=>$sex,
		$phone = input('get.phone'); // 手机号
		$email = input('get.email'); // 邮箱
		$qq = input('get.qq'); // qq
		// $place = input('get.place'); // 居住地

		$data = Db::name('user')->update(['id'=>$id, 'username'=>$username, 'phone'=>$phone, 'email'=>$email, 'qq'=>$qq]);	
		// dump($data);
 		if($data){
            return json_encode(['state' => 1]);
        }else{
            return json_encode(['state' => 0]);
        }
	}


	// 用户禁用，启用
	// public function member_jq()
	// {
	// 	$id = input('get.id');
 //        $status = input('get.status');
 //        // $list = Db::name('user')->where(['id', $id])->setFiled('status', $status);
 //        $list = Db::table('tplay_user')->update(['id'=>$id,'status'=>$status]);  


 //        if($data){
 //            return json_encode(['state' => 1]);
 //        }else{
 //            return json_encode(['state' => 0]);
 //        }
	// }



	// 删除用户
	public function member_del()
	{
		$id =  input('get.id');

		$data  = Db::name('user')->where('id', $id)->delete();
		
		if($data){
            return json_encode(['state' => 1]);
        }else{
            return json_encode(['state' => 0]);
        }
	}

	// 多删用户
	public function member_delduo()
	{
		$id = input('post.data');
		echo $id;
	}

	

}
