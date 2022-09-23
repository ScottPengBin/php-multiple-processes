<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/11 0011
 * Time: 下午 6:10
 */
// 非血缘关系进程间互相通信

// 该进程我们做为消息发送进程

// 一个write 进程，多个read 进程 如果有一个read进程接收到数据之后，其它read进程就没有机会读取数据了
// 一个read 进程，多个write 进程

// 以上测试没有任何进程



$file = "fifo_x";

if (!posix_access($file,POSIX_F_OK)){
    if (posix_mkfifo($file,0666)){
        fprintf(STDOUT,"create ok\n");
    }
}

$fd = fopen($file,"w");

while (1){

    $data = fgets(STDIN,128);
    if ($data){

        $len = fwrite($fd,$data,strlen($data));

        fprintf(STDOUT,"pid=%d write bytes=%d\n",getmypid(),$len);
    }
}

fclose($fd);