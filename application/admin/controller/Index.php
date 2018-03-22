<?php

namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;
use \think\Validate;
use think\Request;
use think\Pager;
use app\admin\model\User as UserModel;
//后台主页
class Index extends Controller
{

	public function index() 
	{
	// 定义后台页面展示方法
		if(empty(Session::get('rid')))
		{
			$this->error('请登录', 'admin/index/login');
		}


		$username = Session::get('username');
        $this->assign('username',$username);
        $uid = Session::get('id');

        $rid = Session::get('rid');
         $rolename = Session::get('rolename');
         //var_dump($rid);die;
         //
         // 查询用户权限
         $result = Db::name('access')->alias('r')->where('role_id',"$rid")->join('node n','r.node_id=n.id')->where('pid','0')->select();
         $result1 = Db::name('access')->alias('r')->where('role_id',"$rid")->join('node n','r.node_id=n.id')->where('pid','<>','0')->select();
         $this->assign('result1',$result1);
         $this->assign('result',$result);
         //var_dump($result1);
         //var_dump($result);die;

        $username = Session::get('username');

        $this->assign('username',$username);

		return $this->fetch();
	}
	public function index1()
	{
		$rid = Session::get('rid');
		$rolename = Session::get('rolename');

		// var_dump($rid);die;

		// 查询用户权限
         $result = Db::name('access')->alias('r')->where('role_id',"$rid")->join('node n','r.node_id=n.id')->select();
         $data['result'] = $result;
         echo json_encode($data);

	}

	// 登录
	public function login()
	{
        
		return $this->fetch();
	}


	// 登录验证信息
    public function panduan()
    {
        $user = new UserModel();
        //var_dump($_POST);
        if(!empty($_POST['username'] && !empty($_POST['password']))){
            $username = $_POST['username'];
            $password = $_POST['password'];
           /* $result = $user->panduan($username);*/
            $result=Db::name('user')->alias('a')->where('quanxian',1)->where(['username'=>$username,'password'=>$password])->join('role_user b','b.user_id = a.id')->join('role c','c.rid=b.role_id')->select();
          // dump($result);
        /* if(!$res){
            return json(['status'=>0,'msg'=>"您输入错误，请重新登录",'url'=>'/admin/user/login']);
        }*/
            // var_dump($result);

                if(!empty($result)){
                    $rid = $result[0]['rid'];
                    $username = $result[0]['username'];
                    $rolename = $result[0]['rolename'];
                    $quanxian = $result[0]['quanxian'];
                    $uid = $result[0]['id'];
                    Session::set('rid',$rid);
                    Session::set('username',$username);
                    Session::set('rolename',$rolename);
                    Session::set('quanxian',$quanxian);
                    Session::set('id',$uid);
                    // var_dump($rid);
                    // var_dump(Session::get('rid'));die;
                    $this->success('登录成功','/admin/index/index');
                }else{
                    $this->error('您不是管理员无法登陆');
               }


        }else{
            $this->error('用户名或密码不能为空');
        }
    }



	// 欢迎页面展示
	public function welcome()
	{

		return $this->fetch();
	}


	// 未找到页面
	public function notFound()
	{
		return $this->fetch();
	}

	// 退出登录
	public function clear()
	{
		Session::clear();
		$this->success('退出成功', '/index/index/index');
	}




	// 加密
	public function jia($str)
	{
		if (is_string($str)) {
			return base64_encode(strrev($str));
		} else {
			return false;
		}
	}

}