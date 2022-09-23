<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/11 0011
 * Time: 下午 6:12
 */

// 接收消息进程
$file = "fifo_x";

if (!posix_access($file,POSIX_F_OK)){
    if (posix_mkfifo($file,0666)){
        fprintf(STDOUT,"create ok\n");
    }
}
$fd = fopen($file,"r");
stream_set_blocking($fd,0);
while (1){

    $data = fread($fd,128);
    if ($data){

        fprintf(STDOUT,"pid=%d recv:%s\n",getmypid(),$data);
    }
}

fclose($fd);

// 命名管道通信：可以用于父子[血缘]进程间通信，也可以用于非血缘进程间通信

