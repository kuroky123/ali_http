<?php
define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "";
                    break;
            }
            echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }

    private function receiveText($object)
    {
        $funcFlag = 0;
        switch ($object->Content)
        {
            case "pay":
                $contentStr=$this->MysqlMsg_ios();
                $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
                break;
            case "news":
                $arr_item=array("Title"=>"TestTitle","Description"=>"TestDescription","PicUrl"=>"http://img5.imgtn.bdimg.com/it/u=1564181002,2731864085&fm=21&gp=0.jpg","Url"=>"115.28.194.142/index.html");
                $contentStr = $this -> transmitNews($object, $arr_item);
                break;
            default:
                $contentStr = "你发送的内容为：".$object->Content;
                $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
                break;
        }
        
        return $resultStr;
    }
    
    private function receiveEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "欢迎关注好吃不过饺子";
            case "unsubscribe":
                break;
            case "CLICK":
                $contentStr=$this->MysqlMsg_ios();
                break;
            default:
                break;      

        }
        if (is_array($contentStr)){
            $resultStr = $this->transmitNews($object, $contentStr);
        }else{
            $resultStr = $this->transmitText($object, $contentStr);
        }
        return $resultStr;
    }

    private function transmitText($object, $content, $funcFlag = 0)
    {
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        <FuncFlag>%d</FuncFlag>
        </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $funcFlag);
        return $resultStr;
    }

    //private function transmitNews($object, $arr_item, $funcFlag = 0)
    private function transmitNews($object, $arr_item)
    {
        //首条标题28字，其他标题39字
        if(!is_array($arr_item))
            return;
        $itemTpl = "<item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        $newsTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <ArticleCount>%s</ArticleCount>
        <Articles>
        $item_str</Articles>   
        </xml>";
        //<Content><![CDATA[]]></Content>
        //<FuncFlag>%s</FuncFlag>
        //$resultStr = sprintf($newsTpl,  $object->ToUserName,$object->FromUserName, time(), count($arr_item), $funcFlag);
        $resultStr = sprintf($newsTpl,$object->ToUserName,$object->FromUserName, time(), count($arr_item));
        return $resultStr;
    }

    public function MysqlMsg_ios()
    {
        require_once 'mysql_config.php';
        $sql1= "select * from 301_revenue where query_type=1 order by query_time desc limit 1";
        $sql2="select * from 301_revenue where query_type=2 order by query_time desc limit 1";
        $conn = mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
        mysql_select_db($mysql_database,$conn);
        $result1 = mysql_fetch_row(mysql_query($sql1));
        $result2 = mysql_fetch_row(mysql_query($sql2));
        $data1=explode(",",$result1[2]);
        $data2=explode(",",$result2[2]);
        print_r($data1);
        echo $data2[4];
        $total_today=0;
        $total_last=0;
        $total_today1=0;
        $total_last1=0;
        foreach ($data1 as $i){
            $total_today=$total_today+$i;
        }
        foreach ($data2 as $j){
            $total_last=$total_last+$j;
        }
        foreach (range(0,23) as $k){
            if($data1[$k]==0){
                break;
            }
            $total_today1=$total_today1+$data1[$k];
            $total_last1=$total_last1+$data2[$k];
        }
        $string="查询时间\t\t".$result1[0]."\n时间\t\t昨日\t\t今日\n0\t\t".$data2[0]."\t\t".$data1[0]."\n1\t\t".$data2[1]."\t\t".$data1[1]."\n2\t\t".$data2[2]."\t\t".$data1[2]."\n3\t\t".$data2[3]."\t\t".$data1[3]."\n4\t\t".$data2[4]."\t\t".$data1[4]."\n5\t\t".$data2[5]."\t\t".$data1[5]."\n6\t\t".$data2[6]."\t\t".$data1[6]."\n7\t\t".$data2[7]."\t\t".$data1[7]."\n8\t\t".$data2[8]."\t\t".$data1[8]."\n9\t\t".$data2[9]."\t\t".$data1[9]."\n10\t\t".$data2[10]."\t\t".$data1[10]."\n11\t\t".$data2[11]."\t\t".$data1[11]."\n12\t\t".$data2[12]."\t\t".$data1[12]."\n13\t\t".$data2[13]."\t\t".$data1[13]."\n14\t\t".$data2[14]."\t\t".$data1[14]."\n15\t\t".$data2[15]."\t\t".$data1[15]."\n16\t\t".$data2[16]."\t\t".$data1[16]."\n17\t\t".$data2[17]."\t\t".$data1[17]."\n18\t\t".$data2[18]."\t\t".$data1[18]."\n19\t\t".$data2[19]."\t\t".$data1[19]."\n20\t\t".$data2[20]."\t\t".$data1[20]."\n21\t\t".$data2[21]."\t\t".$data1[21]."\n22\t\t".$data2[22]."\t\t".$data1[22]."\n23\t\t".$data2[23]."\t\t".$data1[23]."\n合计\t\t".$total_last1."\t\t".$total_today1."\n合计2\t\t".$total_last."\t\t".$total_today;
        return $string;
    }
}
?>
