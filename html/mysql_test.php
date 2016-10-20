<?php 
require_once 'mysql_config.php';
        $sql1= "select * from 301_revenue where query_type=1 order by query_time desc limit 1";
        $sql2="select * from 301_revenue where query_type=2 order by query_time desc limit 1";
        $con = mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
        $$db_selected = mysql_select_db($mysql_database,$con);
        $result1 = mysql_fetch_row(mysql_query($sql1,$con));
        $result2 = mysql_fetch_row(mysql_query($sql2,$con));
        $data1=explode(",",$result1[2]);
        $data2=explode(",",$result2[2]);
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
        echo $string;
?>
