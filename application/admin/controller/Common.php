<?php
// +----------------------------------------------------------------------
// | Tplay [ WE ONLY DO WHAT IS NECESSARY ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://tplay.pengyichen.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 听雨 < 389625819@qq.com >
// +----------------------------------------------------------------------


namespace app\admin\controller;

use \think\Cache;
use \think\Controller;
use think\Loader;
use think\Db;
use \think\Cookie;
class Common extends Controller
{
    /**
     * 清除全部缓存
     * @return [type] [description]
     */
    public function clear()
    {
        if(false == Cache::clear()) {
        	return $this->error('清除缓存失败');
        } else {
        	return $this->success('清除缓存成功');
        }
    }


    /**
     * 图片上传方法
     * @return [type] [description]
     */
    public function upload($module='admin',$use='admin_thumb')
    {
        if($this->request->file('file')){
            $file = $this->request->file('file');
        }else{
            $res['code']=1;
            $res['msg']='没有上传文件';
            return json($res);
        }
        $module = $this->request->has('module') ? $this->request->param('module') : $module;//模块
        $web_config = Db::name('webconfig')->where('web','web')->find();
        $info = $file->validate(['size'=>$web_config['file_size']*1024,'ext'=>$web_config['file_type']])->rule('date')->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . $module . DS . $use);
        if($info) {
            //写入到附件表
            $data = [];
            $data['module'] = $module;
            $data['filename'] = $info->getFilename();//文件名
            $data['filepath'] = DS . 'uploads' . DS . $module . DS . $use . DS . $info->getSaveName();//文件路径
            $data['fileext'] = $info->getExtension();//文件后缀
            $data['filesize'] = $info->getSize();//文件大小
            $data['create_time'] = time();//时间
            $data['uploadip'] = $this->request->ip();//IP
            $data['user_id'] = Cookie::has('admin') ? Cookie::get('admin') : 0;
            if($data['module'] = 'admin') {
                //通过后台上传的文件直接审核通过
                $data['status'] = 1;
                $data['admin_id'] = $data['user_id'];
                $data['audit_time'] = time();
            }
            $data['use'] = $this->request->has('use') ? $this->request->param('use') : $use;//用处
            $res['id'] = Db::name('attachment')->insertGetId($data);
            $res['src'] = DS . 'uploads' . DS . $module . DS . $use . DS . $info->getSaveName();
            $res['code'] = 2;
            addlog($res['id']);//记录日志
            return json($res);
        } else {
            // 上传失败获取错误信息
            return $this->error('上传失败：'.$file->getError());
        }
    }

    /**
     * 登录
     * @return [type] [description]
     */
    public function login()
    {
        if(Cookie::has('admin') == false) { 
            if($this->request->isPost()) {
                //是登录操作
                $post = $this->request->post();
                //验证  唯一规则： 表名，字段名，排除主键值，主键名
                $validate = new \think\Validate([
                    ['name', 'require|alphaDash', '用户名不能为空|用户名格式只能是字母、数字、——或_'],
                    ['password', 'require', '密码不能为空'],
                    ['captcha','require|captcha','验证码不能为空|验证码不正确'],
                ]);
                //验证部分数据合法性
                if (!$validate->check($post)) {
                    $this->error('提交失败：' . $validate->getError());
                }
                $name = Db::name('admin')->where('name',$post['name'])->find();
                if(empty($name)) {
                    //不存在该用户名
                    return $this->error('用户名不存在');
                } else {
                    //验证密码
                    $post['password'] = password($post['password']);
                    if($name['password'] != $post['password']) {
                        return $this->error('密码错误');
                    } else {
                        Cookie::set("admin",$name['id'],7200); //保存新的,最长为2小时
                        //记录登录时间和ip
                        Db::name('admin')->where('id',$name['id'])->update(['login_ip' =>  $this->request->ip(),'login_time' => time()]);
                        //记录操作日志
                        addlog();
                        //Db::name('admin_log')->data(['ip' =>  $this->request->ip(),'create_time' => time(),'name'=>'管理员登录','admin_id'=>$name['id']])->insert();
                        return $this->success('登录成功,正在跳转...','admin/index/index');
                        //$this->redirect('admin/index/index');
                    }
                }
            } else {
                return $this->fetch();
            }
        } else {
            $this->redirect('admin/index/index');
        }   
    }

    /**
     * 管理员退出，清除名字为admin的cookie
     * @return [type] [description]
     */
    public function logout()
    {
        Cookie::delete('admin');
        if(!empty(Cookie::get('admin'))) {
            return $this->error('退出失败');
        } else {
            return $this->success('正在退出...','admin/common/login');
        }
    }
}
