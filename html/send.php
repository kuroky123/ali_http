<?php
	header("Content-Type: text/html;charset=utf-8");
	date_default_timezone_set('Asia/Shanghai');
	require_once 'send_message.php';
	require_once 'mysql_config.php';
	$wechatObj = new wechatSendMessage();
	$open_id=array('o99EQt2gGIQR9IesZPGDlN8eI5pk','o99EQt4ejIivvBeWMBEpLUYDc4Zw');
	
	$date=date("Y-m-d");
	$sql= "select * from exhibition where '".$date."' between date_add(start_time, interval -3 day) and end_time";
	$conn = mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
    mysql_select_db($mysql_database,$conn);
    mysql_query("SET NAMES utf8");
    $result=mysql_query($sql);
    $row = mysql_fetch_row($result);
    if(!$row){ 
	} 
	else { 	
		$echo_string="";
		while($line = mysql_fetch_row($result)){
	    	$echo_string=$echo_string."\n".$line[0]."\n".$line[1]."~".$line[2];
		}
		$message="近期展会情况:\n".$row[0]."\n".$row[1]."~".$row[2].$echo_string;
		$wechatObj -> mass_send_openid($open_id,'text',urlencode($message));
	}
    
	$text="";
?>