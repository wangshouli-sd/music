<?php

namespace app\index\controller;

define( 'APPID', '15a9668eb16930');
define( 'TOKEN', '9d73ec7214e22e9889d75f7fbbc0e357');


class Open51094{
	private $appid;
	private $token;
	private $return_uri;
	private $access_token;
	private $url = 'http://open.51094.com/user/auth.html';

	function __construct(){
		$this->appid = APPID;
		$this->token = TOKEN;
	}

	function me( $code ){
		#$this->getAccessToken();
		$params=array(
				'type'=>'get_user_info',
				'code'=>$code,
				'appid'=>$this->appid,
				'token'=>$this->token
			);
		return $this->http( $params );
	}


	private function http( $postfields='', $method='POST', $headers=array()){
		$ci=curl_init();
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		if($method=='POST'){
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if($postfields!='')curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
		}
		$headers[]="User-Agent: 51094PHP(open.51094.com)";
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ci, CURLOPT_URL, $this->url);
		$response=curl_exec($ci);
		curl_close($ci);
		$json_r=array();
		if(!empty( $response ))$json_r=json_decode($response, true);
		return $json_r;
	}
}
?>