<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/3 0003
 * Time: 下午 5:20
 */
// 信号集

// 信号集是指信号的集合
// 主程序可以选择阻塞某些信号，被信号的信号集称为阻塞信号集，信号屏蔽字Block
// 当进程阻塞了某个信号[通过pcntl_sigpromask来设置信号屏蔽字]，如果在运行期间接收到了
// 阻塞的信号时，这个信号的处理程序不会被执行，这个信号会放在被挂起的信号集里[也叫信号未决集]
// sigpending()
// pcntl_sigpromask 用来设置进程的信号屏蔽字

pcntl_signal(SIGINT,function($signo){

    fprintf(STDOUT,"pid=%d 接收到了信号:%d\n",getmypid(),$signo);
});
// 设置进程的信号屏蔽字｜信号阻塞集
$sigset = [SIGINT,SIGUSR1];
pcntl_sigprocmask(SIG_BLOCK,$sigset);
$i=10;
while ($i--){

    pcntl_signal_dispatch();
    fprintf(STDOUT,"pid=%d do something...\n",getmypid());
    sleep(1);
    if ($i==5){

        fprintf(STDOUT,"时间到，准备解除阻塞...\n");
        //解除信号屏蔽
        //$oldset 会返回之前阻塞的信号集｜信号屏蔽字
        pcntl_sigprocmask(SIG_UNBLOCK,[SIGINT,SIGUSR1],$oldset);
        print_r($oldset);
    }
}

