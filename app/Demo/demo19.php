<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/3 0003
 * Time: 下午 6:34
 */
// 发送信号
// 1) kill -s 信号编号｜信号名字  进程PID
// 2) 在程序中使用posix_kill 给一个指定的进程或是进程组发送信号
// 3) pcntl_alarm SIGALRM
// 4) 在终端按下特殊键ctrl+c,ctrl+z,ctrl+\
// 5) 网络SIGURG，SIGPIPE,SIGCHLD[当子进程结束的时候]

pcntl_signal(SIGINT,function($signo){

    fprintf(STDOUT,"pid %d 接收到 %d 信号\n",posix_getpid(),$signo);
});
pcntl_signal(SIGALRM,function($signo){

    fprintf(STDOUT,"pid %d 接收到 %d 信号\n",posix_getpid(),$signo);
});
// 创建的子进程都是兄弟进程，父进程ID,组ID都一样


$mapPid = [];
$pid = pcntl_fork();

if ($pid>0){
    $mapPid[] = $pid;

    $pid = pcntl_fork();
    if ($pid>0){

        $mapPid[] = $pid;

        while (1){
            pcntl_signal_dispatch();
            //1)pid>0
            //$pid 进程的标识PID
            //$sig 信号的编号｜信号的名字
            //posix_kill($mapPid[0],SIGINT);
            //2)pid=0 就会向进程组中的每个进程发送信号
            //posix_kill(0,SIGINT);

            //3) pid==-1 最好在自己的机器上
            sleep(2);
        }
        exit(0);
    }

}
// 这里是子进程的运行代码
while(1){

    pcntl_signal_dispatch();
    fprintf(STDOUT,"pid %d ppid=%d,pgid=%d doing...\n",posix_getpid(),
        posix_getppid(),posix_getpgrp());
    sleep(1);
}