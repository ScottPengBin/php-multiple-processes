<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/6 0006
 * Time: 下午 6:13
 */

function showPID()
{
    $pid = posix_getpid();
    fprintf(STDOUT,"pid=%d,ppid=%d,pgid=%d,sid=%d\n",$pid,posix_getppid(),posix_getpgid($pid),posix_getsid($pid));

}

// 创建一个会话
// 1 posix_setsid()
// 1） 不能使用组长进程调用setsid函数，要是硬要调用就会报错
// 2) 我们一般先创建一个子进程，让父进程exit，由子进程调用setsid
// 3) 调用setsid之后，该进程会变成组长进程，同时也会变成会话首进程
// 4) 同时该进程没有控制终端[它没有终端了，你可以认为它没有连接显示器，没有连接键盘]
// 它没有控制终端了，你在终端里输入任何数据都没有反应的
// 大家一定要对本章第一节的概念理解清楚


showPID();

$pid = pcntl_fork();

if ($pid>0){
    exit(0);
}
// 调用这个函数之后，该会话首进程是会断开控制终端的连接[你可以认为不连接显示器和键盘，输入输出单元]
if(-1==posix_setsid()){
    echo "error";
    $errno = pcntl_errno();
    echo pcntl_strerror($errno);
}
else{
    echo "会话创建成功\r\n";
}


showPID();

while (1){
    sleep(2);
}