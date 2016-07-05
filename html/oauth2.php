<?php
require_once 'send_message.php';
$wechatObj = new wechatSendMessage();
if (isset($_GET['code'])){
    $CODE=$_GET['code'];
    echo $CODE;
	$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx83fbd1d95f61bb0c&secret=1e2fa5c5385c135a77bf64a6ec19c8a9&code=".$CODE."&grant_type=authorization_code";
	$json=$wechatObj -> https_request($url,null);
	print_r(json_decode($json));
	//$url2="https://api.weixin.qq.com/sns/userinfo?access_token=OezXcEiiBSKSxW0eoylIeAsR0GmYd1awCffdHgb4fhS_KKf2CotGj2cBNUKQQvj-G0ZWEE5-uBjBz941EOPqDQy5sS_GCs2z40dnvU99Y5AI1bw2uqN--2jXoBLIM5d6L9RImvm8Vg8cBAiLpWA8Vw&openid=oLVPpjqs9BhvzwPj5A-vTYAX3GLc";
}else{
    echo "NO CODE";
}
?>