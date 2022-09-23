<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/1 0001
 * Time: 下午 4:45
 */
// 进程调度

// pcntl_fork 创建了一个子进程，这个时候就会存在父进程和子进程
// cpu先调度哪个进程
// sleep / pcntl_wait

// pcntl 封闭了可以控制进程优先级的函数
// 进程的观察：ps | top
// 在Linux系统中，一般把进程/线程称为任务Task
//PID USER      PR  NI    VIRT    RES    SHR S  %CPU  %MEM     TIME+ COMMAND

// PR priority 进程的优先级
// NI nice 进程的nice值 nice值越小，则优化级越高
// 进程的nice越小，则进程的PR优先级越高，cpu就先运行这个进程

// 总结：
$nice = $argv[1];
$start = time();
$count = 0;

$pid = pcntl_fork();

if ($pid==0){

    fprintf(STDOUT,"child process pid=%d,nice=%d\n",posix_getpid(),pcntl_getpriority());

    pcntl_setpriority($nice,getmypid(),PRIO_PROCESS);

    fprintf(STDOUT,"child process pid=%d,nice=%d\n",posix_getpid(),pcntl_getpriority());

    while (1){

        $count++;
        if (time()-$start>5){
            break;
        }
    }
}else{
    fprintf(STDOUT,"parent process pid=%d,nice=%d\n",posix_getpid(),pcntl_getpriority());

    pcntl_setpriority($nice,getmypid(),PRIO_PROCESS);

    fprintf(STDOUT,"child process pid=%d,nice=%d\n",posix_getpid(),pcntl_getpriority());

    while (1){

        $count++;
        if (time()-$start>5){
            break;
        }
    }

}

fprintf(STDOUT,"pid=%d,nice=%d,count=%d\n",posix_getpid(),pcntl_getpriority(),$count);