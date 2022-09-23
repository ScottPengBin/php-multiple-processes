<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/11 0011
 * Time: 下午 4:47
 */
// 进程间通信
// php pcntl进程扩展相关函数
// pcntl_fork来启动一个新的子进程

// ELF可执行文件启动之后，就是一个进程了
// 以前的程序是直接运行在物理内存上的，这样子的话会有几个问题
// 1)物理内存实际上有限的，100M,A进程50M,B进程50M,C进程，以前就会把A进程的数据暂时写入硬盘缓存，硬盘和内存来回
// 读写，内存使用效率不高
// 2)进程A可能恶意修改进程B的内存上的数据了，甚至恶意的攻击。

// 后来搞了进程虚拟地址virtual Address ,cpu--->virtual address ---->MMU[memory management unit] 映射转换到---->物理内存
// 每个进程都有自己独立的虚拟内存地址了，就可以达到进程间互相隔离

// 进程间要互相通信，就必须要依赖操作系统内核实现了
// 也就是说由操作系统内核提供一个缓冲区，进程A,进程B操作这个缓冲区[读写数据]来实现通信的

//IPC:inter-process communication
// Linux进程间通信有以下几种方式
// 1) 管道[用于单个机器上具有血缘关系的进程间通信，比如父子进程]] [匿名管道pipe，命名管道FIFO] unix IPC posix封装的 posix_mkfifo
// 2) 中断信号 [只能用于进程间异步事件通知] unix IPC
// 3) system V IPC 标准提供的进程间通信[消息队列，信号量，共享内存]  php进程扩展只是封闭了system V IPC
//    posix IPC [消息队列，信号量，共享内存]
// 4) socket stream 通信 可以实现跨机器进程间通信

// 进程1－－－－〈系统内核开辟数据缓冲区〉－－－－>进程2
// php源码 github 在官方源码库直接搜索pcntl_fork [fork函数]

// 命名管道  FIFO First in First out 实际是内核实现类似队列

// 1 先实现父子进程间通信
$file = "fifo_x";

if (!posix_access($file,POSIX_F_OK)){
    if (posix_mkfifo($file,0666)){
        fprintf(STDOUT,"create ok\n");
    }
}

$pid = pcntl_fork();
if ($pid==0){

    $fd = fopen($file,"r");

    $data = fread($fd,5);

    if ($data){

        fprintf(STDOUT,"read process pid=%d,recv:%s\n",getmypid(),$data);
    }
    exit(0);
}
$fd = fopen($file,"w");
// write 进程先执行的概率要大一些
$len = fwrite($fd,"hello",5);
fprintf(STDOUT,"write process pid=%d,write len=%d\n",posix_getpid(),$len);
fclose($fd);

$pid = pcntl_wait($status);
if ($pid>0){

    fprintf(STDOUT,"exit pid=%d\n",$pid);
}




