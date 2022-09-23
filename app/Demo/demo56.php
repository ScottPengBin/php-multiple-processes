<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/15 0015
 * Time: 下午 9:24
 */
//unix域tcp类型套接字进程间通信
$file = "unix_abc1";
$sockfd = socket_create(AF_UNIX,SOCK_STREAM,0);
//ip 走网卡
if (socket_connect($sockfd,$file)){
    fprintf(STDOUT,"connect ok\n");


    $pid = pcntl_fork();
    if ($pid==0){

        while (1){
            $len = socket_recv($sockfd,$data,1024,0);
            if ($len){

                fprintf(STDOUT,"recv from server:%s\n",$data);
            }
            if(strncasecmp(trim($data),"quit",4)==0){
                break;
            }

        }
        exit(0);
    }
    while (1){

        $data = fread(STDIN,128);
        if ($data){

            socket_send($sockfd,$data,strlen($data),0);
        }
        if(strncasecmp(trim($data),"quit",4)==0){
            break;
        }
    }
}
$pid = pcntl_wait($status);
fprintf(STDOUT,"exit pid=%d\n",$pid);