<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/1 0001
 * Time: 下午 3:43
 */
// exec 函数的功能：用来执行一个程序
// pcntl的进程扩展，pcntl_exec 这个函数，它内部的系统调用是execve

// exec 一般的用法是父进程先创建一个子进程，然后子进程调用这个函数
// 正文段[代码段]+数据段会被新程序替换，它的一些属性会继承父进程，PID并没有发生变化


function showID($str)
{
    $pid = posix_getpid();
    fprintf(STDOUT,"%s pid=%d,ppid=%d,gpid=%d,sid=%d,uid=%d,gid=%d\n",
        $str,
        $pid,
    posix_getppid(),
    posix_getpgrp(),
    posix_getsid($pid),
    posix_getuid(),
    posix_getgid()
        );
}
showID("parent:");

$pid = pcntl_fork();//clone
if ($pid==0){

    showID("child:");
    //pcntl_exec("demo9_1.php",["a"],["ab"]);
    //pcntl_exec("/usr/bin/php",["demo9_1.php","abc","123"],["test"]);
    pcntl_exec("demo9_2",["a","b","c"]);

    echo "hello,world\n";
}

$pid = pcntl_wait($status);
if ($pid>0){

    fprintf(STDOUT,"exit pid=%d\n",$pid);
}