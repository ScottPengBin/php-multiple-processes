<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/15 0015
 * Time: 下午 8:58
 */
//unix域tcp类型套接字进程间通信
// 命名unix域【本地域】套接字  【tcp,dup】
// 在创建好unix域套接字时一定要绑定地址，地址是一个文件
//1) 创建socket文件 socket
$file = "unix_abc1";
$sockfd = socket_create(AF_UNIX,SOCK_STREAM,0);
//2)
socket_bind($sockfd,$file);
//3)
socket_listen($sockfd,5);//监听队列
//4
// IO 复用
$connfd = socket_accept($sockfd);//accept

if ($connfd){

    while (1){

        $data = socket_read($connfd,1024);
        if ($data){

            fprintf(STDOUT,"recv from client:%s\n",$data);
            socket_write($connfd,$data,strlen($data));
        }
        if(strncasecmp(trim($data),"quit",4)==0){
            break;
        }
    }
}


socket_close($connfd);
socket_close($sockfd);



