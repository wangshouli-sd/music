<?php
namespace app\admin\model;

use think\Model;

class Musicinfo extends Model
{

	public function inquire($info)
	{
		foreach ($info as $key => $value) {
			$re = $this->where('vid' , $info['$key']['vid'])->find();
			if (!is_null($re))
			{
				$info[$key]['vid'] = $re->vid;    // 音乐的id
				$info[$key]['title'] = $re->title;	// 音乐名称
				$info[$key]['vimg'] = $re->vimg;       //封面
				$info[$key]['content'] = $re->content;     // 音乐简介
				$info[$key]['vpath'] = $re->vpath; 	// vpath路径
				
			}

		}
		return $info;
	}
	
}