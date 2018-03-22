<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\index\model\User as UserModel;
use app\index\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;
class Index extends controller

{

    public function top()
    {	
       //var_dump(Session::get('username'));die;
        if (!empty(Session::get('username'))) {
            $yonghu = Session::get('username');
            $this->assign('yonghu',$yonghu);
        }
        
        return $this->fetch();

    }



  public function bottom()
    {
         return $this->fetch();

    }





// index 页面的方法
    public function index()
    {

        $video = new VideoModel();
        $user = new UserModel;
        $category = new CategoryModel();
        //$lala查询一级分类
        $lala = $category->selcat();
        $this->assign('lala',$lala);
        // $lala1s查询所有分类
        $lala1 = $category->selcat1();
        $this->assign('lala1',$lala1);

        //查询全部用户资料
        $qbzl = $user->userList();
        $this->assign('qbzl',$qbzl);


        // get
       
   /*   $cid = $_GET['cid'];
        $this->assign('cid',$cid);*/

        //查询音乐
        //$order = 'bofangliang';
        //$shi6 = $video->order(8,$order);
        $shi6 = Db::name('video')->select();
        $this->assign('shi6',$shi6);
    //var_dump($shi6);die;
        
        //fuzhi
        $i = 0;
        $this->assign('i',$i);

        //var_dump(Session::get('username'));die;
        if (!empty(Session::get('username'))) {
            $yonghu = Session::get('username');
            $this->assign('yonghu',$yonghu);
            $touxiang = Session::get('touxiang');
            $this->assign('touxiang',$touxiang);
        }
        
        return $this->fetch();

    }








     public function clear()
    {
        Session::clear();
        $this->success('退出成功', 'index/index/index');
    }






public function fenye()
{
// 引入文件
  include '/Page.php';
  //得到总的条数
 /*  $shi6 = Db::name('video')->select();
   $vid = $_GET['vid'];
   $bf = Db::name('video')->where('vid',$vid)->select();*/
   
       /* $this->assign('shi6',$shi6);*/
 // 查询状态为1的用户数据 并且每页显示10条数据
  $list = Db::name('video')->where('dabankuai', $shi6)->paginate(8);
  
  // 把分页数据赋值给模板变量list
  $this->assign('list', $list);
  // 渲染模板输出
  
  echo json_encode($list);
}









    

}
