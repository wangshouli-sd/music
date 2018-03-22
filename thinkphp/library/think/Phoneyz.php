<?php

namespace think;

class Phoneyz{
	public $randNum;
	protected $phoneNumber;
	public function __construct($phoneNumber){
		$this->phoneNumber = $phoneNumber;
	}
	public function getYzm(){
		//https://api.ucpaas.com/{SoftVersion}/Accounts/{accountSid}/{function}/{operation}?sig={SigParameter}

		$SoftVersion = '2014-06-30';

		$Account = 'Accounts';

		$accountSid = '770bcfd1ebf6a7dba500d56d97f420b0';

		$function = 'Messages';

		$operation = 'templateSMS';

		$tken = '29ebaa9b1893694c354ce5be415395ae';

		$time = date("YmdHis");

		$SigParameter = strtoupper(md5($accountSid.$tken.$time));

		$Authorization = base64_encode($accountSid.':'.$time);

		$header = [
			'Accept:application/json',
			'Content-Type:application/json;charset=utf-8',
			'Authorization:'.$Authorization,
		];


		/*
		POST/2014-06-30/Accounts/e03bc9106c6ed0eaebfce8c368fdcd48/Messages/templateSMS?sig=769190B9A223549407D2164CAE92152E
		Host:api.ucpaas.com
		Accept:application/json
		Content-Type:application/json;charset=utf-8
		Authorization:ZTAzYmM5MTA2YzZlZDBlYWViZmNlOGMzNjhmZGNkNDg6MjAxNDA2MjMxODUwMjE=
		{
		 "templateSMS" : {
		    "appId"       : "e462aba25bc6498fa5ada7eefe1401b7",
		    "param"       : "0000",
		    "templateId"  : "1",
		    "to"          : "18612345678"
		    }
		}
		 */
		$this->randNum = substr(str_shuffle('0123456789'),0,4);

		$data = [
			'templateSMS'=>[
				'appId'=>'846b792adfe8463a8326ce2cff89a2f2',
				'param'=>"$this->randNum",
				'templateId'=>"154707",
				'to'=>"$this->phoneNumber",
			]
		];

		$body = json_encode($data);

		$url = 'https://api.ucpaas.com/'.$SoftVersion.'/'.$Account.'/'.$accountSid.'/'.$function.'/'.$operation.'?sig='.$SigParameter;

		$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$result = curl_exec($ch);
			curl_close($ch);
			//var_dump($result);
	}
}