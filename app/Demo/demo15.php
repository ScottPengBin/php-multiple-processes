<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/2 0002
 * Time: 下午 7:33
 */
// 中断信号
// 信号 是指软件中断信号，简称软中断
// 中断信号处理程序[信号处理函数，信号捕捉函数]完以后，就会返回继续执行主程序

// 中断源：就是产生中断信号的单元
// 1) 在终端按下按键产生的中断信号 ctrl+c,ctrl+z,ctrl+\
// 2) 硬件异常
// 3) 在终端使用kill 来发送中断信号
// 4) posix_kill 函数 pcntl_alarm 函数
// 5) 软件产生的中断信号 SIGURG[TCP/IP],SIGALRM

// 中断响应
// 对信号的处理
// 1) 忽略
// 2) 执行中断处理函数[捕捉信号执行信号处理函数]
// 3) 执行系统默认
// signal ===> 动作[忽略，默认，执行用户编写好的信号处理函数]

// 中断返回
// 就是指中断服务程序运行完之后返回

// 信号对进程的影响
// 1)直接让进程终止
// 2)让进程停止  SIGCONT 可以唤醒进程到前台继续运行
// SIGSTOP 让进程停止之后
//[1]+  Stopped  [1] 是 作业｜工作 作业编号 job
// ctrl+z 它会让进程丢到后台去停止[背景] [前台][前景]


// pcntl
// pcntl_alrm pcntl_signal pcntl_signal_dispatch  pcntl_sigpromask ...
// 作业控制｜工作管理

echo posix_getpid();
while (1){
    ;
}

