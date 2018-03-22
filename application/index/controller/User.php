<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Ucpaas;
use think\Session;
use think\Phoneyz;
use  think\Open51094;
use app\index\model\User as UserModel;
use app\admin\model\Video as VideoModel;
use app\index\model\Category as CategoryModel;
// use app\index\user\Open51094;
class User extends Controller
{
   
    public function  login() {

        return $this->fetch();

   }

    public function  login1() {
        // 写一个变量等于html里面提交的密码名
       
        //var_dump($_POST);die;
       if (empty($_POST['username'])) {
          $this->error('账号不能为空');
          // die;
        } else {
          
             $username = $_POST['username'];
              
        }
        // 写一个变量等于html里面提交的用户名
        
       if (empty($_POST['enpassword'])) {
          $this->error('密码不能为空');
        } else {
          $password = $_POST['enpassword'];
          
        }


//查询名字密码   'name'是表里的字段名,"$name"提交的名
        $result = Db::name('user')->where('username',"$username")->find();
//判断提交的（input框输入的密码是不等于查询的）
      

         if($result['password'] == $password) {
            Session::set('username',$username);
            $touxiang = $result['touxiang'];
            Session::set('touxiang',$touxiang);
            $id = $result['id'];
            Session::set('id',$id);
           
            $this->success('登陆成功','index/index/index');
        } else{
           $this->success('登陆失败'); 
        }  

   }







//第三方登陆
//第三方登录
 public function other()
    {
        $open = new Open51094();
        // dump($open);
        $code = $_GET['code'];
       // dump($code);die;
        $me = $open->me($code);
        // dump($me);
        $username = $me['username'];
        $touxiang = $me['touxiang'];
        $sex = $me['sex'];


        $res = Db::table('tplay_user')->where('username','=',"$username")->select();
        // dump($res);
        if (!$res) {
            //添加到数据库中
            $result = Db::table('tplay_user')->insert(['username' => "$username",'touxiang' => "$touxiang",'sex' => "$sex"]);
            // dump($result);die;
            if (!$result) {
                $this->error('登录失败，请重新登录', '/index/user/login');
                exit;
            }
        }
        $uid = Db::table('tplay_user')->where('username','=',"$username")->value('id');
        // dump($uid);die;
        Session::set('id', $uid);
        Session::set('username', $username);
        $this->success('登录成功','/index/index/index');
    }



//短信验证码

    function code()
    {
        /*$phone = '17777787662';
        $name = 'admin';*/
       
            $phone = trim($_POST['number']);
            $phoneyz = new Phoneyz($phone);
            $phoneyz->getYzm();
            $pcode = $phoneyz->randNum;
            
          if($pcode){
            Session::set('code',$pcode);
            echo json_encode(['state'=> 1]);
            //echo 111;
            //echo $pcode;
        }else{
            //$this->success('','/index/user/forgot');
            echo json_encode(['state'=> 0]);
            //echo 222;
        }

    }









   // 注册

    public function register() {

        return $this->fetch();

   }

   public function register1() {
     // 写一个变量等于html里面提交的密码名
   
    // dump($_POST);
    // die;
    // // 写一个变量等于html里面提交的用户名
    $name1 = $_POST['user'];
    $pwd1 = $_POST['spassword'];
    $phone1 = $_POST['phone'];

    //var_dump($phone1);die;
    $qq1 = $_POST['qq'];
   $email1 = $_POST['email'];



//判断验证码是否相等
         $yzm = trim($_POST['yzm']);
        $pyzm = Session::get('code');
        //var_dump($pyzm);die;
        if($yzm == $pyzm){
            
            //$this->success('','/index/user/login');
        }else{
            $this->success('验证码错误');
            die;
        }

        $result1 = Db::table('tplay_user')->insert(['username'=>$name1, 'password'=>$pwd1, 'phone'=>$phone1, 'qq'=>$qq1, 'email'=>$email1]);




        //判断邮箱格式是否正确
    // if ($_POST['email'] != '')
     /*if (！empty($_POST['email']))
     {
            $email = $_POST['email'];
            $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";

      if(preg_match($pattern,$email)){
            $user->email = $email;
            $user->save();
           } else{  
               $this->error('邮箱格式错误！');
          }


      }*/



if($result1) {
            $this->success('注册成功','index/index/index');
        } else{
           echo '注册失败'; 
        }
   




    }




// 查询插入数据库

    




// 找回密码


public function zhaohui1 ()
{

        /*$category = new CategoryModel();
        $lala = $category->where('parentid','=',0)->select();

        $this->assign('lala',$lala);
        return $this->fetch();*/






        include './mail/mail.php';

      // 拿到法国拉的验证码
        $to = trim($_POST['to']);
        //$to = '383148823@qq.com';
        $title = '邮箱验证码';
        // 生成四位数的验证码
        $content = substr(str_shuffle('0123456789'),0,4);
        Session::set('content',$content);
        // 三个参数，发送给谁，发送的标题，内容
        $data = sendMails($to,$title,$content);
        if($data){
            echo json_encode(['state'=> 1]);
            //echo 111;
        }else{
            echo json_encode(['state'=> 0]);
            //echo 222;
        }
        //var_dump($content);


}

public function zhaohui()
{


         //$id = Session::get('id');
           //var_dump($id);die;
           
 //var_dump($_FILES);die;
      if (!empty($_POST)) {
        $co =  Session::get('content');
        if (empty($co)) {
               $this->success('请获取验证码');
                die;
             } 
          if (empty($_POST['content'])) {
               $this->success('请获取验证码');
                die;
          }    
          if ($co != $_POST['content']) {
                $this->success('验证码错误');
                die;
           }
           //$user->id = $id;
//若旧密码不为空
          if ($_POST['username'] != '') {
            $username = $_POST['username'];
                $p = Db::name('user')->where('username',$username)->select();
                if (empty($p)) {
                  $this->success('没有该用户');die;
                } else {
                  $id = $p[0]['id'];
                  $user = UserModel::get($id);
                  $user->id = $id;
                }
                //var_dump($p);die;
           } else {
            $this->success('用户名不能为空');die;
           }
          // 若新密码不为空
          // 

          

           if ($_POST['newpassword'] == '') {
                $this->success('新密码不能为空');die;
           }
   
// 若确认密码不为空
           if ($_POST['renewpassword'] == '') {
                $this->success('确认密码不能为空');
              die;
            }
           if ($_POST['renewpassword'] != $_POST['newpassword']) {
                $this->success('两次密码不一致');
                die;
           }
           $shengfen = $_POST['renewpassword'];
            $user->password = $shengfen;
            $user->save();
          $this->success('修改成功');
        }
        






   
  return $this->fetch();
}




//_________个人资料的修改

  public function gerenziliao (){
    return $this->fetch();
  }
  


  public function gerenziliao1 (){

         $id = Session::get('id');

          // var_dump($id);die;
                   
          $user = UserModel::get($id);
           $user->id = $id;
 //var_dump($_FILES);die;
      if (!empty($_POST)) {
        

            //若性别不为空
            if ($_POST['sex'] != '-1') {
                $sex = $_POST['sex'];
                $user->sex = $sex;
                $user->save();
            }
           
            //若真实姓名不为空
            if ($_POST['name'] != '') {
                $xingming = $_POST['name'];
                $user->xingming = $xingming;
                $user->save();
            }

              if ($_POST['qq'] != '') {
                $qq = $_POST['qq'];
                $user->qq = $qq;
                $user->save();
            }

            //若用户名不为空
            if ($_POST['username'] != '') {
                $username = $_POST['username'];
                $user->username = $username;
                $user->save();
            }
           
            //若省份不为空
            if ($_POST['shengfen'] != '不公开') {
                $shengfen = $_POST['shengfen'];
                $user->place = $shengfen;
                $user->save();
            }
          
        }
        $this->success('修改成功');
    }

//_________个人资料的展示结束


 





// 这是设置头像页面上的z展示


 public function touxiang() {

        return $this->fetch();

   }


// 头像上传提交成功
// public function up(Request $request)
// 
public function touxiang1()
{

                  $id = Session::get('id');
                 // var_dump($id);die;
                   $user = UserModel::get($id);
                  $user->id = $id;
    //若头像不为孔就更新
    //var_dump($_FILES);die;
                  if (!empty($_FILES['touxiang'])) {
                      $touxiang = request()->file('touxiang');
                      $vid = $touxiang->move(ROOT_PATH . 'public' . DS . 'uploads');
                      $vimg = '/uploads/' . $vid->getSaveName();
                      Session::set('touxiang',$vimg);
                     //var_dump(Session::get('touxiang'));die;
                      $user->touxiang = $vimg;

                      $user->save();

                     $this->success('上传成功','index/index/index');
                  }  
                  else{
                     echo '上传失败'; 
                  }


}







//音乐文件上传
public function wenjian() {

            $result = Db::name('category')->where('parentid != 0')->select();
        //var_dump($result);
        $this->assign('result',$result);
        return $this->fetch();

   }






/*// 音乐添加页面展展示
  public function music_add()
  {
    
        $result = Db::name('category')->where('parentid != 0')->select();
        //var_dump($result);
        $this->assign('result',$result);
    return $this->fetch();
  }
*/


  //上传音乐
    public function addVideo()
    {
    // 获取表单上传文件 例如上传了001.jpg
        if (empty($_FILES['vimg']['tmp_name'])) {
          $this->error('上传图片超过规定大小');
        }
        if (empty($_FILES['vpath']['tmp_name'])) {
          $this->error('上传视频超过规定大小');
      }
        $file = request()->file('vimg');
        $video = request()->file('vpath');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>156780000,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        // var_dump($info);
        $vid = $video->validate(['size'=>156780000,'ext'=>'mp3'])->move(ROOT_PATH . 'public' . DS . 'uploads');

        if($info && $vid){
          $vm = new VideoModel();
          $title = $_POST['title'];
          $fenqu = $_POST['fenqu'];

          $category = new CategoryModel();
          $da = $category->selda($fenqu);
          $dabankuai = $da[0]['parentid'];


          $vpath = '/uploads/' . $vid->getSaveName();
          $content = $_POST['content'];
          $zuozhe = $_POST['zuozhe'];
          $vimg = '/uploads/' . $info->getSaveName();
          //$result = $vm->insuser($title,$fenqu,$vimg,$content,$vpath);

          $vm->dabankuai = $dabankuai;
          $vm->title = $title;
          $vm->fenqu = $fenqu;
          $vm->vimg = $vimg;
          $vm->content = $content;
          $vm->vpath = $vpath;
          $vm->zuozhe = $zuozhe;
          $vm->uname = Session::get('id');
          $vm->save();
          $this->success('上传成功');
        // 成功上传后 获取上传信息
        // 输出 jpg
        //echo $info->getExtension();
        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        //echo $info->getSaveName();
        // 输出 42a79759f284b767dfcb2a0197904287.jpg
        //echo $info->getFilename();
        }else{
          $this->error('上传失败');
        // 上传失败获取错误信息
        //echo $file->getError();
        }
    }












// s上传文件的php

 public function wenjian1() {
// public function up(Request $request){
            
    // 获取表单上传文件
    $files = request()->file('music');
    $item = [];
    foreach($files as $file){
    // 移动到框架应用根目录/public/uploads/ 目录下
    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');


    if($info){
    $item[] = $info->getRealPath();
    }else{
    // 上传失败获取错误信息
    $this->error($file->getError());
    }
    }
    $this->success('文件上传成功'.implode('<br/>',$item));
}






// 这是设置个人签名的z展示

public function pinglun (){
  return $this->fetch();
}


//这是评论的PHP

public function pinglin1 ()
{
    

    $pinglun = $_POST['content'];

     $result1 = Db::table('tplay_tiezi')->insert(['content'=>$pinglun]);



      if($result1) {
            $this->success('评论成功','index/index/index');
        } else{
           echo '评论失败'; 
        }

}




  // 这是设置个人资料的z展示














// var_dump();die

// 这是设置密码安全的z展示

public function mimaanquan (){
  return $this->fetch();
}


// 这是密码安全的PHP


public function mimaanquan1 (){

         $id = Session::get('id');
           //var_dump($id);die;
                   
          $user = UserModel::get($id);
           $user->id = $id;
 //var_dump($_FILES);die;
      if (!empty($_POST)) {
        
//若旧密码不为空
            if ($_POST['oldpwd'] != '') {
                $p = Db::name('user')->where('id',$id)->select();
                if ($p[0]['password'] != $_POST['oldpwd']) {
                  $this->success('旧密码错误');die;
                }
                //var_dump($p);die;
           } else {
            $this->success('旧密码不能为空');die;
           }
          // 若新密码不为空

           if ($_POST['newpwd'] == '') {
                $this->success('新密码不能为空');die;
           }
   
// 若确认密码不为空
           if ($_POST['repwd'] == '') {
                $this->success('确认密码不能为空');
              die;
           if ($_POST['repwd'] != $_POST['newpwd']) {
                $this->success('两次密码不一致');
                die;
           }
           $shengfen = $_POST['repwd'];
            $user->password = $shengfen;
            $user->save();
          
        }
        $this->success('修改成功');
    }

  }


}






