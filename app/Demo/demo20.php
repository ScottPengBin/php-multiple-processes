<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/3 0003
 * Time: 下午 6:52
 */

pcntl_signal(SIGCHLD,function($signo){

    fprintf(STDOUT,"pid %d 接收到 %d 信号\n",posix_getpid(),$signo);

    //pcntl_alarm(2);
    $pid = pcntl_waitpid(-1,$status,WNOHANG);
    if ($pid>0){
        fprintf(STDOUT,"pid %d 退出了\n",$pid);
    }
});

// 时间到之后，这个定时就会被 清理掉
//pcntl_alarm(1);
//pcntl_alarm(3);

$pid = pcntl_fork();
if ($pid>0){
    while (1){
        pcntl_signal_dispatch();
        fprintf(STDOUT,"pid %d ppid=%d,pgid=%d doing...\n",posix_getpid(),
            posix_getppid(),posix_getpgrp());
        sleep(1);

    }
}else{

    fprintf(STDOUT,"pid %d child do...\n",posix_getpid());
    exit(10);
}
