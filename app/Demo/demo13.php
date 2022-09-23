<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/2 0002
 * Time: 下午 5:19
 */
// 一个程序启动之后就是一个进程了，进程的数据肯定是内存中的
// 正文段+数据段，内存中的一些数据也会写入到proc文件系统中
// proc/PID
// 1)ps PID,PPID,UID,GID,STAT,COMMAND
// 2)top
// 3)pstree

// proc/PID
// Linux 系统中一般会把进程/线程称为任务Task
//TTY 是一个物理终端｜伪终端pts/0 ssh /telnet

// https://github.com/torvalds/linux/blob/master/Documentation/filesystems/proc.rst
// https://www.man7.org/linux/man-pages/man5/proc.5.html
// soft limit hard limit
// open files 10
// pcntl_fork 10
echo posix_getpid();
//print_r(getenv());//打印进程的环境参数表
$fd = fopen("/demo13_1.txt","w");
// socket
// has open many files
// 文件创建屏蔽字 umask
print_r(posix_getlogin());
// 一个进程启动之后，必然会启动一个主线程，主线程有自己的入口函数[php 是用c开发的]
// 一般是main 函数
// thread 1 子线程
// pthread_create(入口函数);//c
// std::thread t (入口函数)/ c++
// 当启动多个线程之后，如果是多核的[线程数量<=cpu核数]并发执行，就是线程数量超过了核数，那么就是模拟的“并发”
// java
// 多线程开发：php用不到｜java/c++/c
// linux 库. so
// 系统调用
// set user ID,set group ID

while (1){

    ;
}