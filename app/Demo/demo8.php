<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/1 0001
 * Time: 下午 2:01
 */

// 1) return exit 函数 正常终止退出
// 2) 中断信号 异常终止退出
// 进程不管是何种方式退出，都会有一部分数据驻留在内存中，比如说终止状态，所以父进程必须使用pcntl_wait函数来回收终止进程所占用的系统资源
// 僵尸进程：就是子进程已经退出，但父进程还没有回收[pcntl_wait] Z

// 我们可以通过函数判断进程的退出方式，我们可以获取终止状态码，我们可以获取到它的中断信号编号

echo posix_getpid();

$pid = pcntl_fork();//
if ($pid==0){

    while(1){
        fprintf(STDOUT,"pid=%d child process do...\n",posix_getpid());
        sleep(2);
    }
    //exit(10);
    //return 10;
}

while (1){

    $pid = pcntl_wait($status,WUNTRACED);//它会让父进程以非阻塞方式运行

    fprintf(STDOUT,"exit pid=%d\n",$pid);
    if ($pid>0){

        // 正常退出
        if(pcntl_wifexited($status)){
            fprintf(STDOUT,"正常退出：exit pid=%d,exit-status=%d\n",$pid,pcntl_wexitstatus($status));

        }
        //中断退出
        else if (pcntl_wifsignaled($status)){

            fprintf(STDOUT,"中断退出1：exit pid=%d,exit-status=%d\n",$pid,pcntl_wtermsig($status));
        }
        // 一般是发送SIGSTOP SIGTSTP 要让进程停止
        else if (pcntl_wifstopped($status)){
            fprintf(STDOUT,"中断退出2：exit pid=%d,SIGNUM=%d\n",$pid,pcntl_wstopsig($status));
        }

    }
    fprintf(STDOUT,"PID=%d father process...\n",posix_getpid());
    sleep(3);
}
// kill 它的功能：是用来发送一个中断信号[给进程或者说是一个进程组] man
// 中断信号：它有自己的信号编号和对应的信号名字，信号编号是以非负数值来表示，信号名字是以SIG开头的
// kill -l