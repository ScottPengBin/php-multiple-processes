<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/6 0006
 * Time: 下午 5:39
 */
// 会话
// 会话就是一个进程组，或是多个进程组的集合
// 1 一个会话可以至少有一个控制终端[物理终端，伪终端]
// 2 一个会话至少有一个前台进程组[前台就是指能输入的bin/bash]，其它就是后台进程组
// 3 一个会话如果连接了一个控制终端，就叫控制进程
// 因为这个会话首进程/bin/bash是连接控制终端[伪终端设置驱动程序+tcp/ip 对端的ssh client]的，所以创建的子进程
// 也会继承bin/bash的控制终端[0,1,2标准输出，标准输入，标准错误]
// 因为连接了终端，所以在终端的输入会影响前台进程组，ctrl+c


function showPID()
{
    $pid = posix_getpid();
    fprintf(STDOUT,"pid=%d,ppid=%d,pgid=%d,sid=%d\n",$pid,posix_getppid(),posix_getpgid($pid),posix_getsid($pid));

}

$pid = pcntl_fork();
showPID();
while (1){

    sleep(1);
}
//windows10 [putty工具,ssh client]
//--------------tcp/ip
//              linux sshd 服务
//--------------------sshd 进程 ptmx伪终端主设备文件
//------------------------------伪终端设备驱动程序 [0,1,2] 标准输入，标准输出
//--------------------bin/bash 进程 pts/[0,1,2]
//-------------------------------

//pts<------> <伪终端设备驱动程序><----------->ptmx[ssh client]