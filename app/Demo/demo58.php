<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/15 0015
 * Time: 下午 9:46
 */

$file = "unix_abc3";//客户端

$serverFile = "unix_abc2";//服务端

$sockfd = socket_create(AF_UNIX,SOCK_DGRAM,0);

socket_bind($sockfd,$file);//绑定这个的目的是为了接收进程接收到数据以后，可以返回给本进程


$pid = pcntl_fork();
if ($pid==0){

    while (1){
        $len = socket_recvfrom($sockfd,$buf,1024,0,$unixClientFile);
        if ($len){

            fprintf(STDOUT,"recv from server:%s\n",$buf);
        }
        if(strncasecmp(trim($buf),"quit",4)==0){
            break;
        }

    }
    exit(0);
}

while (1){

    $data = fread(STDIN,128);
    if ($data){

        socket_sendto($sockfd,$data,strlen($data),0,$serverFile);
    }
    if(strncasecmp(trim($data),"quit",4)==0){
        break;
    }
}
$pid = pcntl_wait($status);
fprintf(STDOUT,"exit pid=%d\n",$pid);