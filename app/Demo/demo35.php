<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/11 0011
 * Time: 下午 5:48
 */
// 就是测试一下:当读端关闭的时候，如果写端还在继续写，这个时候就无法写进去了，并且还会产生中断信号SIGPIPE
$file = "fifo_x";

if (!posix_access($file,POSIX_F_OK)){
    if (posix_mkfifo($file,0666)){
        fprintf(STDOUT,"create ok\n");
    }
}

$pid = pcntl_fork();
if ($pid==0){

    $fd = fopen($file,"r");
    stream_set_blocking($fd,0);//将文件设置为非阻塞方式
    $i=0;
    while (1){
        // $len =socket_read()
        $data = fread($fd,5);// read 系统调用函数，不管有没有数据，甚至写端还没有写数据都不管，read函数立马返回
        echo $i++;
        echo "\r\n";
        //只要调用read函数不会阻塞到有数据，不会挂起，会立马返回
        //如果说是阻塞，系统调用会挂起，并不会立马返回
        if ($data){

            fprintf(STDOUT,"read process pid=%d,recv:%s\n",getmypid(),$data);
            break;
        }
    }

    exit(0);//如果以非阻塞方式不管有没有读取到数据，程序会非常快的结束
}

$fd = fopen($file,"w");
stream_set_blocking($fd,0);
// write 进程先执行的概率要大一些
$len = fwrite($fd,"hello",5);//如果有缓冲空间就能写进去，也有可能写不进去
fprintf(STDOUT,"write process pid=%d,write len=%d\n",posix_getpid(),$len);
fclose($fd);

$pid = pcntl_wait($status);
if ($pid>0){

    fprintf(STDOUT,"exit pid=%d\n",$pid);
}