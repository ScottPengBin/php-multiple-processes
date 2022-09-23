<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28 0028
 * Time: 下午 9:31
 */
// 第三章第二节 进程退出

// 一个程序启动之后，变成了一个进程，进程在以下情况会退出
// 1）运行到最后一行语句
// 2) 运行时遇到return 时
// 3) 运行时遇到exit()函数的时候
// 4) 程序异常的时候
// 5) 进程接收到中断信号
//c /c++
// 一个进程要么是正常结束，要么是异常结束[信号有关]，不管是何种方式导致进程退出
// 它都有一个终止状态码，进程结束时并不会真的退出，还会驻留在内在中，父进程可以使用wait[pcntl_wait]函数
// 来获取进程的终止状态码同时该函数会释放终止进程的内存空间。否则会容易造成僵尸进程过多占用大量的内存空间

// 僵尸进程：就是指子进程已经结束，但是父进程还没用使用wait/wait_pid来回收

/**


root     15541 49.2  2.8 116456 23624 pts/0    R+   21:43   0:56 /home/soft/php/bin/php demo6.php
root     15542 50.0  0.7 116456  5904 pts/0    R+   21:43   0:57 /home/soft/php/bin/php demo6.php

ps -aux
root     15550 91.2  2.8 116456 23652 pts/0    R+   21:47   0:17 /home/soft/php/bin/php demo6.php
root     15551  0.0  0.0      0     0 pts/0    Z+   21:47   0:00 [php] <defunct>

R Running
Z zombile 僵尸
<defunct> 死人，死者
这种进程我们称为：僵尸进程

一个进程运行时，会在/proc/PID这具目录文件
如果：我们开发一个守护进程的web项目，如果说开启了大量的子进程，并且没有回收，那么服务器的内存和存储空间
可能会被挤满，所以我们必须回收。

进程的退出和回收
exit
 *
 *
 **/
$pid = pcntl_fork();
if (0===$pid){

    fprintf(STDOUT,"我是子进程pid=%d，运行后我就没事了.\n",posix_getpid());

    exit(10);

}else{
    fprintf(STDOUT,"我是父进程pid=%d\n",posix_getpid());
    //sleep(1);

    //pcntl_wait
    //1) 一 如果说没有子进程，调用pcntl_wait可能会返回错误
    //2) 如果说子进程还没有结束，调用pcntl_wait就会阻塞父进程
    //3) 我们给pcntl_wait函数传递第三个参数option可以让父进程不阻塞[这个后面说]

    $exitPid = pcntl_wait($status);
    if ($exitPid>0){
        fprintf(STDOUT,"pid=%d,子进程已经挂了，它的终止状态码：%d,并且已经完全释放了它所占用的资源...\n",$pid,$status);
    }else{
        fprintf(STDOUT,"wait error...\n");
    }
    while(1){
        fprintf(STDOUT,"我在打印...\n");
        sleep(3);
    }
}
//posix
fprintf(STDOUT,"PID = %d 结束\n",getmypid());

