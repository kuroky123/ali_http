<?php 
header("Content-Type: text/html;charset=utf-8");
class wechatSendMessage{
	public function get_access_token()
	{
		$appid = "wx83fbd1d95f61bb0c";
		$appsecret = "1e2fa5c5385c135a77bf64a6ec19c8a9";
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$jsoninfo = json_decode($output, true);
		$access_token = $jsoninfo["access_token"];
		return $access_token;
	}


	public function https_request($url, $data = null)
	{
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)){
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
	}

	public function mass_send_openid($open_id,$type,$data)
	{
		$msg = array('touser' => $open_id);
		$msg['msgtype'] = $type;
		switch($type){
			case 'text':
				$msg['text'] = array('content'=>$data);
				break;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$this -> get_access_token();
		$res = $this -> https_request($url,urldecode(json_encode($msg)));
		return json_decode($res,true);
	}
}
?>