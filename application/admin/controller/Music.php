<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use app\admin\model\Musicinfo;
use app\admin\model\Category as CategoryModel;
use app\admin\model\Video as VideoModel;
use lib\Pager;
use think\Session;
use \think\Validate;
use app\admin\model\Img;

class Music extends controller 
{

	// 音乐列表展示
	public function music_list() 
	{
		// 展示音乐列表
		$music = Db::name('video')->select();
		// $page = $music->render();	

		$count = count($music);

		// dump($count);
		$this->assign('count', $count);
		
		// dump($music);
		$this->assign('music', $music);
		// $this->assign('page', $page);
		return $this->fetch();

	}



	// 板块
	public function system_category()
	{	

		$data = Db::name('category')->select();
		// dump($data);
		$data1 = $this->GetTree($data, 0 , 1);
		// dump($data1);
		// $this->assign('data', $data);
		$this->assign('data1', $data1);	
		return $this->fetch();
	}	

		//无限极分类

	function GetTree($arr,$pid,$step){
    global $tree;
    foreach($arr as $key=>$val) {
    	// parentid 父类id 
        if($val['parentid'] == $pid) {
            $flg = str_repeat(' |&nbsp; ',$step);
            $val['bkname'] = $flg.$val['bkname'];
            $tree[] = $val;
            // 子类id
            $this->GetTree($arr , $val['cid'] ,$step+1);
        	}
   	 	}
     	return $tree;
	}
	

	// 删除版块
	public function  system_category_del()
	{
		$islock = input('get.islock'); 	
		$cid = input('get.cid');
		$data = Db::name('category')->where('cid', $cid)->where('islock',1)->delete();
		// 只能删除小版块 即islock=1
		// $data = Db::name('category')->where('cid', $cid)->delete();
		// 接收json数据
		if ($data) {
			return json_encode(['state'=>1]);
		} else {
			json_encode(['state'=>0]);
		}
	}



	// 添加板块的页面
	public function system_category_add()
	{
		$data = Db::name('category')->where('parentid', '=', 0)->select();
		$this->assign('data', $data);
		return $this->fetch();
	}
	// 添加版块 
	
	public function system_category_add1()
	{
		$parentid = input('get.parentid');
		$bkname = input('get.bkname');

		$data = ['parentid'=>$parentid, 'bkname'=>$bkname, 'jibie'=>2]; // 默认添加小版块 

		$result = Db::name('category')->insert($data);

		if ($result) {
			return json_encode(['state'=>1]);
		} else {
			json_encode(['state'=>0]);
		}

	}



	// 版块修改
	public function system_category_edit()
	{
		$cid = $_GET['cid'];

		$data = Db::name('category')->where('cid', '=' ,  $cid)->select();

		// dump($data);

		$data1 = Db::name('category')->where('parentid', '=' , 'cid')->select();
		// dump($data1);
		$this->assign('data',$data);
		$this->assign('data1',$data1);	

		// dump($data);
		return $this->fetch();

	}
	// 版块修改页面
	public function system_category_edit1()
	{	


		$parentid = input('get.parentid');
        $bkname = input('get.bkname');
        
        $cid= input('get.cid');
		$data = Db::name('category')->update(['bkname'=>$bkname, 'cid'=>$cid]);

		
		if ($data) {
			return json_encode(['state'=>1]);
		} else {
			json_encode(['state'=>0]);
		}
		// dump($data);
	}

	// 歌曲管理展示页面
	public function music()
	{


		return $this->fetch();
	}


	// 音乐展示页面

	public function music_show() 
	{
		return $this->fetch();
	}

	// 音乐添加页面展展示
	public function music_add()
	{
		
        $result = Db::name('category')->where('parentid != 0')->select();
        //var_dump($result);
        $this->assign('result',$result);
		return $this->fetch();
	}



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
        //var_dump($info);
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




	// // 上传七牛云文件
	// public function qiniuyun($a,$b){
	// 	// 需要填写你的 Access Key 和 Secret Key
	// 	$accessKey ="7rJ-Q7UBOxS6w8p79h6-EWsLbz3jXHQ8Ze_12aZ7";
	// 	$secretKey = "Kxs_2-66YBKtRV3WWloD73bvs62jQfjGnAtIGVZk";
	// 	$bucket = "youngyang";
	// 	// 构建鉴权对象
	// 	$auth = new Auth($accessKey, $secretKey);
	// 	// 生成上传 Token
	// 	$token = $auth->uploadToken($bucket);
	// 	// 要上传文件的本地路径
	// 	$filePath = $b;
	// 	// 上传到七牛后保存的文件名
	// 	$key = $a;
	// 	// 初始化 UploadManager 对象并进行文件的上传。
	// 	$uploadMgr = new UploadManager();
	// 	// 调用 UploadManager 的 putFile 方法进行文件的上传。
	// 	list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
	// }

    // public function music_add()
    // {
    	

    //     //上传文件的名称
    //     $key = $_FILES['file']['name'];


    //     if(!$key){
    //         echo "<script type='text/javascript'>alert('上传视频不能为空');</script>";die;
    //     }
    //     //dump($_FILES);
    //     //上传文件类型
    //     $vname = $_FILES['file']['type'];


    //     //获取文件的临时副本的名称
    //     $filePath=$_FILES['file']['tmp_name'];


    //     //获取token值
    //     $accessKey='QL9YoWcmvdLQuoZZjAKQWzwBexF0rgRYbXNEmwnU';
    //     $secretKey ='p6zP7cvP63wd4mYrZ2LRK5v5j-ipqlgckDVOIV26';



    //     // 获取自己空间的token值
    //     $accessKey='AIeur85-13fBsH8DRCgSQ41K66A34nDmw7_n72ck';
    //     $secretKey ='04iAS_ogwoAFk9S5psI-qCfl7fdbH5i8ouXU7Uj1';


    //     // 初始化签权对象
    //     $auth = new Auth($accessKey, $secretKey);
    //     $bucket = 'music';   // 空间名字
    //     // 生成上传Token
    //     $token = $auth->uploadToken($bucket);
    //     $uploadMgr = new UploadManager();




    //     // 调用 UploadManager 的 putFile 方法进行文件的上传。
    //     list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

    //         // 获取去封面
    //         $fengmian = 'http://'.WAILIAN.$key.'?vframe/jpg/offset/1';


    //         //视频链接
    //         $url ='http://'.WAILIAN.$ret['key'];


    //         //标题
    //         $vtitle = $_POST['vtitle'];

    //         //作者
    //         $uid = Session::get('uid');

    //         //版块
    //         $pid = 2;


    //         //举办地
    //         $vaddress = $_POST['vaddress'];

    //         //video
    //         $vparentid = 0;


    //         //插入数据库
    //         $data=[

    //             'uid'=>$uid,
    //             'vtitle'=>$vtitle,
    //             'pid'=>$pid,
    //             'vurl'=>$url,
    //             'vaddress'=>$vaddress,
    //             'vpicture'=>$fengmian,
    //             'vparentid'=>$vparentid
    //         ];




    //         $res = Db::table('video')->insert($data);

    //         return $this->fetch();


    // }


	// 音乐分类展示
	public function music_category() 
	{
		return $this->fetch();
	}	

		// 音乐类型添加页面展展示
	public function music_category_add() 
	{
		return $this->fetch();
	}
	// 删除音乐
	public function music_del()
	{
		$vid = $_POST['vid'];
		$vid = input('post.vid');

		$data = Db::name('video')->where('vid', $vid)->delete();

		if ($data) {
			return json_encode(['state'=>1]);
		} else {
			json_encode(['state'=>0]);
		}

	}
}



