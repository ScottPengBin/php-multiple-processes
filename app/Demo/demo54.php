<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/15 0015
 * Time: 下午 8:49
 */
// 用于具有血缘关系进程间通信【父子，兄弟】全双工
$fd = stream_socket_pair(AF_UNIX,SOCK_DGRAM,0 );

$fd[0];//用于读 stdin
$fd[1];//用于写 sdout



$pid = pcntl_fork();
if ($pid==0){
// recv进程
    while (1){

        $data = fread($fd[0],128);
        if($data){

            fprintf(STDOUT,"recv data:%s\n",$data);

        }
        if(strncasecmp(trim($data),"quit",4)==0){
            break;
        }
    }
    exit(0);
}
//send 进程
while (1){


    $data = fread(STDIN,128);
    if($data){

        fwrite($fd[1],$data,strlen($data));

    }

    if(strncasecmp($data,"quit",4)==0){
        break;
    }
}

$pid = pcntl_wait($status);
fprintf(STDOUT,"exit pid=%d\n",$pid);