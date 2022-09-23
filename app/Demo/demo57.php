<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/15 0015
 * Time: 下午 9:41
 */
//unix域udp类型套接字进程间通信

$file = "unix_abc2";

$sockfd = socket_create(AF_UNIX,SOCK_DGRAM,0);

socket_bind($sockfd,$file);//绑定一个地址


while (1){

    $len = socket_recvfrom($sockfd,$buf,1024,0,$unixClientFile);
    if ($len){

        fprintf(STDOUT,"recv data:%s,file=%s\n",$buf,$unixClientFile);

        socket_sendto($sockfd,$buf,strlen($buf),0,$unixClientFile);
    }
    if(strncasecmp(trim($buf),"quit",4)==0){
        break;
    }
}