<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use \think\Validate;
use app\admin\Model\Admin;

// 判断是否登录成功
class Auth extends Controller
{	
	protected $admin;
	protected $allNodes;
	protected $is_login = [''];
	public function _initialize()
	{
		$this->admin = new Admin;
		if (!$this->checklogin() && in_array('*', $this->is_login)) {
			// $this->redirect('admin/auth/login');
		}
		// 都能进入的页面
		$freeNode = [
			'/admin/Index/index',
			'/admin/Index/welcome',
			'/admin/Auth/login',
			// '/admin/Auth/dologin',
			// '/admin/Auth/tuichu',
			// '/admin/Auth/cg',
			// '/admin/Auth/shibai',
		];
		if (!empty(session::get('id'))) {
			
			$allNodes = $this->getRole();	
		}
	}
	public function getRole()
	{
		$role = []; // 放入角色id
		$auths = []; // 放入权限id
		$allNodes = [];

		$adminID = session::get('id');
		// 查询当前用户所拥有的角色  二维数组
		$admin = DB::name('admin_role')->where('admin_id',$adminID)->select();
		if ($admin) {
			foreach ($admin as $key => $value) {
				$role[] = $value['role_id'];
			}
			// dump($role);
			if ($role) {
				foreach ($role as $key => $v) {
					// 根据角色id查询权限id
					$auths[] = DB::name('role_auth')->distinct(true)->where('role_id',$v)->field('role_id,auth_id')->select();
				}
				// dump($auth);
				foreach ($auths as $key => $value) {
					foreach ($value as $key => $val) {
						$auth[] = $val['auth_id'];
					}
				}
				// $auth = array_unique($auth);
				// dump($auth);
				if (!empty($auth)) {
					foreach ($auth as $key => $value) {
						$nodes[] = DB::name('auth')->where('id',$value)->select();
					}
					// dump($nodes);
					if ($nodes) {
						foreach ($nodes as $key => $value) {
							foreach ($value as $key => $v) {
								$tmp_nodes = $v['nodes'];
								$allNodes[] = $tmp_nodes;
							}
						}
						// dump($allNodes);
					}
				} else {
					return [];
				}
			}
			return $allNodes;
		}
		
	}
	// 登录界面
	public function login()
	{
		return $this->fetch();
	}
	// 退出
	public function tuichu()
	{
		Session::delete('id');
		$this->redirect('admin/auth/login'); 
	}
	// 成功界面
	public function cg()
	{
		return $this->fetch();
	}
	// 登录失败
	public function shibai()
	{
		return $this->fetch();
	}
	// 判断是否登录成功
	public function dologin(Request $request)
	{
		$name = trim($request->param('user'));
		$pwd = trim($request->param('pwd'));
		$data = $this->admin->checkinfo($name, $pwd);
		//验证验证码
		$validate = new Validate([
			'captcha|验证码'=>'require|captcha'
		]);
		$yzm = [
			'captcha'=>input('surePwd'),
		];
		if ($validate->check($yzm) && $data) {
			if ($data->password == md5($pwd)) {
				session('id', $data->id);
				session('name', $data->name);
				// dump(session('name'));die;
				$this->success();
			} else {
				$this->error();
			}
		} else  {
			$this->error('登录失败');
		}

	}
	// 检查是否登录
	public function checklogin()
	{
		return session('?id');
	}
}