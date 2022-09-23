<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/12 0012
 * Time: 下午 8:26
 */
$key = ftok("demo42.php","x");
$msqid = msg_get_queue($key);
// 第4个参数是接收缓冲区长度，如果设置过小，会反序列化失败【当使用序列化功能的时候】
// 当接收缓冲区大小设置过小并且关闭了反序列化【发送进程发送数据的时候也要进程】
// 但是发送进程发送的数据超过了设置的长度，就会被截断，并且额外的数据会直接丢弃
echo msg_receive($msqid,0,$msgType,3,$msg,false,MSG_NOERROR);
//msgrcv
echo "type=".$msgType."\r\n";
echo $msg;