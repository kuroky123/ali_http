        <?php
        $arr_item=array(array("Title"=>"TestTitle","Description"=>"TestDescription","PicUrl"=>"http://img5.imgtn.bdimg.com/it/u=1564181002,2731864085&fm=21&gp=0.jpg","Url"=>"115.28.194.142/index.html"));
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
            $item_str .= sprintf($itemTpl, $item["Title"], $item["Description"], $item["PicUrl"], $item["Url"]);
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
        echo $resultStr;?>