<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/6 0006
 * Time: 下午 4:44
 */
// 进程组
// bash进程拥有一个终端[输入和输出]，这种终端我们一般也叫控制终端
// 进程组：就是一个或是多个进程的集合，一个进程都有个标识组ID，表示该进程属于哪个进程组

// bash进程启动之后，它会自己setsid把自己设置为会话首进程，也会设置自己为组长进程

$pid = posix_getpid();
fprintf(STDOUT,"pid=%d,ppid=%d,pgid=%d,sid=%d\n",$pid,posix_getppid(),posix_getpgid($pid),posix_getsid($pid));

//bin/bash
//--------php demo22.php  [tcp/ip,linux它有伪终端设备驱动程序会模拟出一个终端]


// 进程：正在执行的程序
//但是这个程序肯定是在bin/bash进程[它是有伪终端]里启动的
//启动之后[execve这个函数给我们启动的]，它会继承一些属性比如说组ID,会话ID，同时也会继承父进程已经打开的文件描述符
//0,1,2 [伪终端里的标准输入，标准输出，标准错误，pts,ptmx]

//bin/bash启动之后，会调用setsid这个函数把自己设置为会话首进程，也会设置自己为组长进程
//同时它打开了伪终端从设备文件pts[它有一个伪终端设置驱动程序]
//它是连接一个控制终端的



