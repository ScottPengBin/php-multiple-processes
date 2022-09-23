<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/11 0011
 * Time: 下午 5:59
 */

$file = "fifo_x";

if (!posix_access($file,POSIX_F_OK)){
    if (posix_mkfifo($file,0666)){
        fprintf(STDOUT,"create ok\n");
    }
}

pcntl_signal(SIGPIPE,function($signo){

    fprintf(STDOUT,"signo=%d\n",$signo);


});
$pid = pcntl_fork();
if ($pid==0){

    $fd = fopen($file,"r");
    stream_set_blocking($fd,0);//将文件设置为非阻塞方式
    $i=0;
    while (1){
        // $len =socket_read()
        $data = fread($fd,5);// read 系统调用函数，不管有没有数据，甚至写端还没有写数据都不管，read函数立马返回

        //只要调用read函数不会阻塞到有数据，不会挂起，会立马返回
        //如果说是阻塞，系统调用会挂起，并不会立马返回
        if ($data){

            $i++;
            fprintf(STDOUT,"read process pid=%d,recv:%s\n",getmypid(),$data);
            if ($i==5){
                fclose($fd);
                break;
            }

        }
    }

    exit(0);//如果以非阻塞方式不管有没有读取到数据，程序会非常快的结束
}

$fd = fopen($file,"w");
stream_set_blocking($fd,0);
while (1){
    pcntl_signal_dispatch();
    // write 进程先执行的概率要大一些
    $len = fwrite($fd,"hello",5);//如果有缓冲空间就能写进去，也有可能写不进去
    fprintf(STDOUT,"write process pid=%d,write len=%d\n",posix_getpid(),$len);

    $pid = pcntl_wait($status,WNOHANG);
    if ($pid>0){

        fprintf(STDOUT,"exit pid=%d\n",$pid);
        //break;
    }
}

fclose($fd);

// 总结：如果读端已经关闭，写端还在继续写数据，是无法继续写了，并且还会产生中断SIGPIPE信号



