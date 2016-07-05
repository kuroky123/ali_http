<?php  
  
    $appid = "wx83fbd1d95f61bb0c";  
    $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=http%3a%2f%2f115.28.194.142%2ffn_callback.php&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';  
    header("Location:".$url);  
  
?>  