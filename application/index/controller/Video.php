<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Upload;
use think\Session;
use think\Page;
use app\index\model\Category as CategoryModel;
use app\index\model\Video as VideoModel;
use app\index\model\User as UserModel;

class Video extends Controller
{

// 退出清除
   public function clear()
    {
        Session::clear();
        $this->success('退出成功', 'index/index/index');
    }





// 除了主页，剩下页面的方法
    public function yinyue()
    {



        $video = new VideoModel();
        $user = new UserModel;
        $category = new CategoryModel();
        //$lala查询一级分类
        //
        $lala = $category->selcat();
        $this->assign('lala',$lala);
        // $lala1s查询所有分类
        $lala1 = $category->selcat1();
        $this->assign('lala1',$lala1);

        // get
        $cid = $_GET['cid'];
        $this->assign('cid',$cid);
     

        //查询音乐
        $shi6 = Db::name('video')->select();
        $this->assign('shi6',$shi6);
    
          //查询全部用户资料
        $qbzl = $user->userList();
        $this->assign('qbzl',$qbzl);

        //推荐视频
        $order1 = 'danmu';
        $tuijian = $video->orderall($order1);
        $this->assign('tuijian',$tuijian);

        //fuzhi
        $i = 0;
        $this->assign('i',$i);

      //var_dump(Session::get('username'));die;
      if (!empty(Session::get('username'))) {
        $yonghu = Session::get('username');
                    $touxiang = Session::get('touxiang');
            $this->assign('touxiang',$touxiang);
        $this->assign('yonghu',$yonghu);
      }
      
        return $this->fetch();

    }
// 页面结束



  

// 这是播放的 哦东西
public function bofang()
{

        // 显示用户头像的
        $video = new VideoModel();
        $user = new UserModel;
        $category = new CategoryModel();
        //$lala查询一级分类
        //
        $lala = $category->selcat();
        $this->assign('lala',$lala);
        // $lala1s查询所有分类
        $lala1 = $category->selcat1();
        $this->assign('lala1',$lala1);

          //查询全部用户资料
        $qbzl = $user->userList();
        $this->assign('qbzl',$qbzl);
        //查询收藏
        $uid = Session::get('id');
        $data1 = Db::name('user')->where('id',$uid)->select();
        // 获得用户搜藏的视频
        $vid = $_GET['vid'];
        

        if (empty($data1)) {
          $shou = 0;
          $this->assign('shou',$shou);
        } else {
          $aa = $data1[0]['vid'];
          if (in_array($vid,explode(',',$data1[0]['vid']))) {
            $shou = 1;
            $this->assign('shou',$shou);
          } else {
            $shou = 0;
            $this->assign('shou',$shou);
          }
        }

// 播放的的内容
  $vid = $_GET['vid'];
   $bf = Db::name('video')->where('vid',$vid)->select();
    $this->assign('bf',$bf);
// 查询的发表的帖子，发表的人，给谁发表的
    $tz = Db::name('tiezi')->where('tid',$vid)->select();
    $this->assign('tz',$tz);


    /*$tt = Db:name('tiezi')->where('tid',$vid)->select();*/
// 结束
// 

    if (!empty(Session::get('username'))) {
            $yonghu = Session::get('username');
            $this->assign('yonghu',$yonghu);
                        $touxiang = Session::get('touxiang');
            $this->assign('touxiang',$touxiang);
        }
    $arr = Db::table('tplay_tiezi')->where('tid',$vid)->select();
    //var_dump($arr);die;
      $cat = $this->tree($arr);
      //var_dump($cat);die;
      $this->assign('cat',$cat);

   return $this->fetch();
}

// 播放结束


 //无限级分类
    static function tree($data,$pid=0,$level=0)
  {
    // var_dump($data);die;
    static $treeList = array();
    foreach($data as $v){
      if($v['parent_id'] == $pid){
        $v['level'] = $level;
        
        $treeList[] = $v;
        self::tree($data,$v['id'],$level+1);
      } 
    }
    //ar_dump($treeList);
    return $treeList;
  }




// 这是收藏的php
public function shoucang1()
{

  $vid = $_GET['vid'];
  $shou = $_GET['shou'];
  // var_dump($vid);die;
  $uid = Session::get('id');
  $data1 = Db::name('user')->where('id',$uid)->select();
  $id = $data1[0]['vid'];
  if ($shou == 0) {
    $a = explode(',',$id);
    $v = '';
    foreach ($a as $key => $value) {
      if ($vid != $value) {
        $v .= $v . ',' . $vid;
      }
    }
  } else {
    $aa = $id[0]['vid'];
    if (empty($aa)) {
      $v = $vid;
    } else {
      $v = $id . ',' . $vid;
    }
  }
  
  $vi = ['vid'=>$v];
  $data = Db::name('user')->where('id',$uid)->update($vi);
  if ($data) {
    $this->success('操作成功');
  }
}












// 搜素的查询
public function sousuo()
{


    $video = new VideoModel();
    $user = new UserModel();
    $category = new CategoryModel();
        //$lala查询一级分类
        
          //
        $lala = $category->selcat();
        $this->assign('lala',$lala);
        // $lala1s查询所有分类
        $lala1 = $category->selcat1();
        $this->assign('lala1',$lala1);

        // get
      // 搜索的内容
    if(!empty($_POST['neirong'])){
      $content = $_POST['neirong'];
      $where = "title like '%$content%'";
      $data = $video->where($where)->select();
      $count = count($data);
      // var_dump($data);
      $userList = $user->userList();
     //var_dump($userList);
      $this->assign('content',$content);
      $this->assign('count',$count);
      $this->assign('data',$data);
      $this->assign('userList',$userList);
    }else{
      $this->error('请输入要搜索的内容');
    }




          //查询全部用户资料
      $qbzl = $user->userList();
      $this->assign('qbzl',$qbzl);

      if (!empty(Session::get('uid'))) {
        $uid = Session::get('uid');

          $ziliao = $user->selu($uid);
          $this->assign('ziliao',$ziliao);
      }

if (!empty(Session::get('username'))) {
            $yonghu = Session::get('username');
                        $touxiang = Session::get('touxiang');
            $this->assign('touxiang',$touxiang);
            $this->assign('yonghu',$yonghu);
        }
   return $this->fetch();
}





// 发表评论

public function fabiao()
{


  // 这是评论的内容
    $uid = Session::get('id');
    $vid = $_GET['vid'];
    $pinglun = $_POST['content'];
     $result1 = Db::table('tplay_tiezi')->insert(['content'=>$pinglun,'tid'=>$vid,'ft_user'=>$uid,'create_time'=>time()]);
     /*$this->assign('$result1',$result1);
*/
// 查询的帖子的所有内容
/*$tz = $tie->();
      $this->assign('qbzl',$qbzl);*/

      
      if($result1) {
            $this->success('评论成功');
        } else{
           echo '评论失败'; 
        }


}
// 评论结束














// 页面的展示
  public function remen()
  {
    return $this->fetch();
  }




// 音乐的展示页面
  public function music()
  {
    return $this->fetch();
  }





  public function mv()
  {
    return $this->fetch();
  }




  public function newmusic()
  {
    return $this->fetch();
  }




  public function zhuanji()
  {
    return $this->fetch();
  }




}
