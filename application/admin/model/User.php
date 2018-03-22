<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
use app\admin\model\User as UserModel;


use app\admin\model\Userinfo;
class User extends Model
{
	//软删除
	use SoftDelete;

	protected $deleteTime = 'delete_time';

	protected $autoWriteTimestamp = true;
	protected $userinfo;	

	// 初始化控制器
	public function _initialize()
	{
		$this->userinfo = new Userinfo;
		
	}

	//查询用户是否存在
	function panduan($name)
    {
        return Db::name('user')->where('username',$name)->find();
    }

    public function userLimit($limit)
    {
        return Db::name('user')->limit($limit)->select();
    }







	public function info () 
	{
		$arr = [];
		// 软删除 
		$re = $this->withTrashed()->select();
		foreach ($re as $key => $value) {
			$arr[$key]['id'] = $value->id;
			$arr[$key]['username'] = $value->username;
			$arr[$key]['phone'] = $value->phone;
			$arr[$key]['sex'] = $value->sex;
			$arr[$key]['create_time'] = $value->create_time;	
			$arr[$key]['delete_time'] = $value->delete_time;	
		}
		return $arr;
	} 

	// 软删除
	public function del($id)
	{
		$re = $this->destory($id);

		if ($re) {
			$info = ['code'=>1];
			echo json_encode($info);
		} else {
			$info = ['code'=>2];
			echo json_encode($info);
		}
	}

	// 保存
	public function active($id)
	{
		$re = $this->save(['delete_time'=>NULL], ['id'=>$id]);
		if ($re) {
			$info = ['code'=>0];
			echo json_encode($info);
		} else {
			$info = ['code'=>1];
			echo json_encode($info);
		}
	}

	// 真正的删除
	public function realdelete($id)
	{
		$re = $this->destory($id, true);
		if ($re) {
		
			$info = ['code'=>0];
			echo json_encode($info);
		} else {
			$info = ['code'=>1];
			echo json_encode($info);
		}			
	}

	// 多删除
	public function delduo($id)
	{
		$re = $this->destory($id, true);
		return $re;

	}

	public function infomation($data)
	{
		$re = select();
	}



}