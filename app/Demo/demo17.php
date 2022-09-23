<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/3 0003
 * Time: 下午 4:32
 */
// 中断信号处理程序

// pcntl 信号处理函数
// $signo  信号编号｜信号名字
// $handler 中断信号处理程序｜信号捕捉函数｜信号处理函数｜信号处理程序
// $restart_syscall = true 这个参数表示是否要重启被中断的系统调用
// 应用程序[php解释器]--->库函数/系统调用函数--->系统内核
// strace 可以跟踪应用程序调用了哪些系统接口函数

// 中断系统调用
// 当进程正在执行系统调用的时候，接收到中断信号，那么这个系统调用就会被中断
// 比如说进程正在写文件
// 无法恢复

// 如果能恢复我们称为：“可重入函数”，否则就是非可重入函数
// 一般来说phper程序不需要关注这个东西，因为由php解释器封闭实现pcntl
// c/c++
// 系统调用函数会返回－1，errno 会设置为EINTR 中断错误  在编写网络程序的时候
// 每个信号都有相应的动作[信号处理程序]
// 1）用户自定义的中断信号处理程序 捕捉
// 2) SIG_DEF 系统默认动作 [结果一般都会让进程终止或是停止，终止+core]
// 3) 忽略 SIG_IGN ignore

// SIGKILL SIGSTOP 这两个不可以捕捉以及忽略，主要用于能可靠的终止或是停止进程

// 进程启动的时候：信号的动作默认是系统行为
// 如果你再编写信号对应的处理程序，就会覆盖掉原来的动作
// 有些信号是可以覆盖掉默认动作，但有些信号不可以，比如说SIGKILL,SIGSTOP

function sigHandler($signo)
{
    fprintf(STDOUT,"pid=%d,我接收到一个信号:%d\n",getmypid(),$signo);
}

// 信号捕捉，自定义信号处理程序 [$this,'']
pcntl_signal(SIGINT,'sigHandler');
pcntl_signal(SIGUSR1,'sigHandler');
pcntl_signal(SIGUSR2,SIG_IGN);
// 子进程会不会继承父进程的信号处理呢？？？

//该信号是无法捕捉[就是说你编写的信号处理程序它是不会执行的]
//pcntl_signal(SIGKILL,'sigHandler');// 不可以这样写  Error installing signal handler for 9
//pcntl_signal(SIGSTOP,'sigHandler');//Fatal error: Error installing signal handler for 19
//pcntl_signal(SIGSTOP,SIG_IGN);
//pcntl_wifexist
// 每个信号都有默认动作：可能会让进程终止｜停止+core

// 一般在中断信号处理函数，不要写太多的业务逻辑
// 一般来说我们经常把中断信号用于通知
//signal(SIGINT,handler)
//^\Quit (core dumped)  ctrl+\  SIGQUIT

// 当父进程创建一个子进程的时候，子进程是继承父进程的中断信号处理程序的
$pid = pcntl_fork();

if ($pid==0){

    //子进程已经重设信号处理程序
    pcntl_signal(SIGUSR1,function($signo){

        fprintf(STDOUT,"子进程我收到中断信号了，然后我没事做了...\n");
    });
}
while (1){

    pcntl_signal_dispatch();

    fprintf(STDOUT,"pid=%d,main process 在做一此事情...\n",getmypid());

    sleep(1);
}
