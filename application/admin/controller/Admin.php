<?php

namespace app\admin\controller;
use think\Controller;
use think\Db;
// use think\Page;
use think\Session;
use app\index\model\User as UserModel;

// 管理员管理
// 角色管理展示
class Admin extends controller 
{
	public function admin_role()
	{
		$result = Db::name('role')->select();
        
        $quanxian = Db::name('access')->alias('r')->join('node n','r.node_id=n.id')->select();
        $this->assign('result',$result);
		$this->assign('quanxian',$quanxian);
		$count = count($result);
		$this->assign('count',$count);
		// var_dump($result);
  //       var_dump($quanxian);

		return $this->fetch();

	}

	// 权限管理展示
	public function admin_permission()
	{
		$result=Db::name('role')->select();
		$quanxian = Db::name('access')->alias('r')->join('node n','r.node_id=n.id')->select();

		$count = count($result);

		$this->assign('result',$result);
		$this->assign('quanxian',$quanxian);
		$this->assign('count',$count);

		// var_dump($result);
		// var_dump($quanxian);
		return $this->fetch();
	}	



	//删除角色
    // public function admin_permission_del()
    // {
        
    //     $uid = $_GET['rid'];
    //     $result = db('role')->where('rid',$uid)->delete();
    //     $result1 = db('access')->where('role_id',$uid)->delete();

    //     var_dump($result);
    //     if($result && $result1){
    //         $this->success('删除成功');
    //     }else{
    //         $this->error('删除失败');
    //     }
    // }

	// 管理员列表

	public function admin_list()
	{
		//查询所有角色权限
   		$result=Db::name('user')->alias('a')->join('role_user b','b.user_id= a.id')->join('role c','c.rid=b.role_id')->select();
   		$this->assign('result',$result);

   		// 数量管理员
   		$count = count($result);
   		$this->assign('count',$count);

        // var_dump($result);
        //$quanxian = Db::name('access')->alias('r')->join('node n','r.node_id=n.id')->select();

        // var_dump($value);
        // $value['allPage'] = $page->allPage();
        // echo json_encode($value);
    
		return $this->fetch();
	}	




	public function admin_add()
    {
        $result = db('role')->select();
        $this->assign('result',$result);
        return $this->fetch();
    }
    //增加管理员
    public function admin_add1()
    {
        $user = new UserModel();
        if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['phone'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $jiaose = $_POST['jiaose'];
            
            $result = $user->where('username',$username)->select();
            //var_dump($result);
            if($result){
                $this->error('该用户已被注册');
            }else{
                $user->username = $username;
                $user->password = $password;
                $user->quanxian = 1;
                $user->phone = $phone;
                $data = $user->save();
                $uid = $user->id;
                $data1 = ['role_id' => $jiaose, 'user_id' => $uid];
                $re = Db::table('tplay_role_user')->insert($data1);
                if($data && $re){
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
        }else{
            $this->error('请填写内容');
        }
    }


	
    public function admin_edit()
    {
        $result = db('role')->select();
        $this->assign('result',$result);
        $uid = $_GET['uid'];
        $this->assign('uid',$uid);
        return $this->fetch();
    }
    //管理员等级修改
    public function admin_edit1()
    {
        $uid = $_POST['uid'];
        $roleid = $_POST['jiaose'];
        //$roleid = 1;
        $result = Db::table('tplay_role_user')->where('user_id', $uid)->update(['role_id' => $roleid]);
        //var_dump($result);
        if ($result) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败','/admin/admin/admin_edit');
        }
       
    }

        //删除用户管理员功能
    public function admin_del()
    {
        $video = new UserModel();
        $uid = $_POST['id'];
        $result = db('role_user')->where('user_id',$uid)->delete();
        $result1 = $video->where('id',$uid)->update(['quanxian'=>0]);
        if($result && $result1){
            return json_encode(['state' => 1]);
        }else{
            return json_encode(['state' => 0]);
        }
    }
    // //多删
    // public function admin_role_duoshan()
    // {
    //     if(!empty($_POST['uid'])){
            
    //         $video = new UserModel();
    //         $uid = $_POST['uid'];
    //         //$uid = '3,4';
    //         $u = explode(',',$uid);
    //         //var_dump($u);
    //         foreach ($u as $key => $value) {
    //         //var_dump($value);
    //             $result = db('role_user')->where('user_id',$value)->delete();
    //             $result1 = $video->where('uid',$value)->update(['quanxian'=>0]);
    //         }
            
    //         //var_dump($result1);
    //         echo json_encode(['sta'=> 1]);
            
    //     }else{
    //         echo json_encode(['sta'=> 2]);
    //     }

    // }



     //删除角色
        public function admin_role_del()
        {
            
            $uid = $_POST['rid'];
            $result = db('role')->where('rid',$uid)->delete();
            $result1 = db('access')->where('role_id',$uid)->delete();
            if($result && $result1){
                return json_encode(['state' => 1]);
            }else{
                return json_encode(['state' => 0]);
            }
        }
    // //多删
    // public function admin_role_duoshan()
    // {
    //     if(!empty($_POST['rid'])){
            
            
    //         $uid = $_POST['rid'];
    //         //$uid = '3,4';
    //         $u = explode(',',$uid);
    //         //var_dump($u);
    //         foreach ($u as $key => $value) {
    //         //var_dump($value);
    //             $result = db('role')->where('rid',$value)->delete();
    //             $result1 = db('access')->where('role_id',$value)->delete();
    //         }
            
    //         //var_dump($result1);
    //         echo json_encode(['sta'=> 1]);
            
    //     }else{
    //         echo json_encode(['sta'=> 2]);
    //     }

    // }



	// 管理员角色添加展示

	public function admin_role_add()
	{
		$result = db('node')->select();
        $this->assign('result',$result);
		return $this->fetch();
	}

		public function admin_role_add1()
	{
	if(!empty($_POST['name']) && !empty($_POST['jiaose'])){
            $username = $_POST['name'];
            $jiaose = $_POST['jiaose'];
            
            $result = Db::name('role')->where('rolename',$username)->find();
            //var_dump($result);
            if($result){
                $this->error('已有该角色名');
            }else{
                //var_dump($jiaose);
                $ins = ['rolename'=>$username];
                $rid = Db::name('role')->insertGetId($ins);
                foreach ($jiaose as $key => $value) {
                    $data1 = ['role_id' => $rid, 'node_id' => $value];
                    $re = Db::table('tplay_access')->insert($data1);
                }
                
                if($rid){
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
        }else{
            $this->error('请填写内容');
        }
		return $this->fetch();
	}
	
	  public function admin_role_edit()
    {
        $result = db('node')->select();
        $this->assign('result',$result);
        $uid = $_GET['rid'];
        $this->assign('id',$uid);
        return $this->fetch();
    }
    //管理员等级修改
    public function admin_role_edit1()
    {
        
        $id = $_POST['id'];
        //var_dump($id);
        $jiaose = $_POST['jiaose'];
        //$roleid = 1;
        $result1 = db('access')->where('role_id',$id)->delete();
        
        foreach ($jiaose as $key => $value) {
            $data1 = ['role_id' => $id, 'node_id' => $value];
            $re = Db::table('tplay_access')->insert($data1);
        }
        //var_dump($result);
        if ($result1) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败','/admin/admin/admin_role_edit');
        }
       
    }





}

