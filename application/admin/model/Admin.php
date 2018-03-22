<?php

namespace app\admin\model;

use think\Model;
use think\Request;
use think\Model\Softdtelete;

class Admin extends Model
{
	// 软删除
	// use SoftDelete;
	protected $deleTime = 'delete_time';
	protected $autoWriteTiemstamp = true;

	public function checkinfo () 
	{
		$name = User::name('admin')->find();
		dump($name);
		$re = User::name('admin')->where('name', $name)->find();

		return $re;
	}
	// 规则
	public function role()
	{
		return $this->belongsToMany('Role', 'admin_role');
	}

		public function username()
	{	
		$re = $this->select();
		return $re;
	}
	// 添加节点
	public function add_role($username, $rolename)
	{
		if (gettype($username) != 'NULL') {
			$info = $this->get(['name'=>$username]);
			if (gettype($info) == 'object') {
				$info->role()->save(['name'=>$rolename]);
			}
		}
		return true;
	}
	// 规则
	public function modifyrole($id,$authid,$rolename)
	{
		$arr = [];
		foreach ($rolename as $key => $value) {
			$arr[] = $value->id;
		}

		$info = $this->get($id);
		if (gettype($info) != 'NULL') {
			$info->role()->detach($arr);
			$info->role()->attach($authid);
			return true;
		}		
	}

	// 列表
	public function list($admin)
	{
		$arr = [];
		foreach ($admin as $key => $value) {
			$arr[$key]['name'] = $value->data['name'];
			$arr[$key]['id'] = $value->data['id'];
			$arr[$key]['phone'] = $value->phone;
			$arr[$key]['email'] = $value->email;
			$arr[$key]['create_time'] = $value->create_time;
			$arr[$key]['delete_time'] = $value->delete_time;
			// dump($value->role);
			$k = $key;
			foreach ($value->role as $key => $value) {
				$arr[$k]['role'][] = $value->data['name'];
			}
		}
		return $arr;
	}
	// 用户名
	public function checkname($name)
	{
		$re = $this->get(['name'=>$name]);
		if($re){
			$info = ['code'=>0,'info'=>'用户名已存在'];
			echo json_encode($info);
		}else{
			$info = ['code'=>1,'info'=>'用户名可用'];
			echo json_encode($info);
		}
	}

	// 信息
	public function doinsert($name,$password,$phone,$email,$adminRole)
	{
		// 添加管理员
		$this->data([
			'name' => $name,
			'password' => md5($password),
			'phone' => $phone,
			'email' => $email
			]);
		$re = $this->save();
		$id = $this->getLastInsID();
		// dump($id);
		// 增加对应角色关系
		$info = $this->get($id);
		$rel = $info->role()->save($adminRole);

		return $re;
	}
	// 禁用
	public function forbidden($id)
	{
		$re = $this->destroy($id);
		if($re){
			$info = ['code'=>1,'info'=>'已禁用'];
			echo json_encode($info);
		}else{
			$info = ['code'=>0,'info'=>'禁用失败'];
			echo json_encode($info);
		}
	}
	// 开启
	public function starting($id)
	{
		$re = $this->save(['delete_time'=>null],['id'=>$id]);
		if($re){
			$info = ['code'=>1,'info'=>'已启用'];
			echo json_encode($info);
		}else{
			$info = ['code'=>0,'info'=>'启用失败'];
			echo json_encode($info);
		}
	}
	// 删除
	public function realdel($id)
	{
		$re = $this->where('id',$id)->delete();
		if($re){
			$info = ['code'=>1,'info'=>'删除成功'];
			echo json_encode($info);
		}else{
			$info = ['code'=>0,'info'=>'删除失败'];
			echo json_encode($info);
		}
	}
	// 多删
	public function realduo($box)
	{
		$re = $this->where('id','in',$box)->delete();
		return $re;
	}
	// 搜索
	public function sousuo($info)
	{
		$last = $this->order('id','desc')->find();
		$lastid = $last->data['id'];
		if (is_string($info)) {
			$re = $this->where('name','like',"%$info%")->select();
		} else if (is_numeric($info) && $info <= $lastid) {
			$re = $this->where('id','like',"%$info%")->select();
		} else if (is_numeric($info) && strlen($info) < 12) {
			$re = $this->where('phone','like',"%$info%")->select();
		}
		if (isset($re)) {
			return $re;
		}
	}
	// 密码修改
	public function pwd($name,$password)
	{
		$result = $this->where('name',$name)->find();
		if (md5($password) == $result->password) {
			$info = ['code'=>1,'info'=>'原密码正确'];
			echo json_encode($info);
		}else{
			$info = ['code'=>0,'info'=>'原密码错误'];
			echo json_encode($info);
		}
	}


}
