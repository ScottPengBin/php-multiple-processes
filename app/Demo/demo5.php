<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28 0028
 * Time: 下午 7:57
 */
// 进程控制
// https://www.man7.org/linux/man-pages
// 一个程序被加载到内在中运行，系统会为这个进程分配相应的标识信息，比强pid,ppid,uid,euid,pgid,sid,gid,egid...

//PID
// STDOUT 标准输出 屏幕终端
// git svn linux
// pstree 可看出进程间的关系 父子，兄弟
// ps -exj | ps aux |ps ...
// PID PPID PGID UID TTY STAT TIME COMMAND
// R 运行状态
// Z zombie 僵尸状态
// S sleeping 是可被系统唤醒
// T stop 停止状态
// D
function printID()
{
    fprintf(STDOUT,"pid=%d\n",posix_getpid());//进程自己的标识PID
    fprintf(STDOUT,"ppid=%d\n",posix_getppid());//父进程的标识PPID
    fprintf(STDOUT,"pgid=%d\n",posix_getpgrp());//进程组长的ID
    fprintf(STDOUT,"sid=%d\n",posix_getsid(posix_getpid()));//会话ID
    fprintf(STDOUT,"uid=%d\n",posix_getuid());//用户标识UID 是指当前登录用户 实际用户UID
    fprintf(STDOUT,"gid=%d\n",posix_getgid());//组ID
    fprintf(STDOUT,"euid=%d\n",posix_geteuid());//有效用户EUID
    fprintf(STDOUT,"egid=%d\n",posix_getegid());//有效组EGID
}

$text = "php is the best language!";

// fork pcntl_fork
fprintf(STDOUT,"现在我的标识是：pid=%d\n",posix_getpid());
//1）这个函数会返回2次[会执行2次，第1次可能是父进程，第2次可能是子进程，父进程运行]
$pid = pcntl_fork();//fork 之后 $pid我们知道它现在是一个普通的变量，它是数据，子进程就能复制

//本行会有2个进程运行 分别是子进程和父进程，a,b [父进程从39行开始运行，子进程是从45行开始运行]
//子进程前面的代码，它不会运行


// shell 终端输入 php demo5.php 之后
// 这个父进程会从第39行开始运行，执行pcntl_fork函数
// 这个函数执行成功之后，会创建一个子进程[子进程会复制父进程的代码段和数据段，ELF文件的结构]
// 然后呢父进程继续执行第56行，然后进程结束，子进程开始从56行开始运行，执行fprintf语句之后，进程结束
// 当父进程调用pcntl_fork函数之后，创建出来的子进程，这个时候就有2个进程，那么父进程和创建出来的子进程
// 哪个进程先运行是无法确定的，也是无法知道的，是由操作系统来决定的，它的进程调度由系统决定。
// 一般情况下：都是父进程先运行，子进程后运行，如果说父进程先运行，先结束，这个时候这个子进程它就没有父亲了
// 这个时候它就成为了孤儿进程，这个时候它会被1号进程接管。
// 变成孤儿进程的后果就是：它跑到后台去运行了，它不在前台运行了。
// 所以我们一般让父进程后结束，先让子进程先运行，
// 当子进程被创建后，它会复制[是写时复制COW copy on write]，父子进程它们是共同使用同一块内存空间
// 当子进程要修改内存空间时，这个时候，系统会复制新的内存空间给子进程修改。COW

// 子进程得到的数据[$pid，它得到的结果是0]
// 父进程必须先让子进程先结束，如果说父进程先结束，子进程被1号进程接管，变成孤儿进程
// 如果说子进程先结束，父进程后结束，这种情况一般来说是正常的，但是父进程应该回收子进程
// 子进程结束时[还会生成一些数据，比如说状态码等其它信息，并没有完全释放，需要父进程回收，我们讲到wait]


if(0==$pid){

    //子进程运行的开始
    fprintf(STDOUT,"pid=%d 我是子进程，我开始运行了.\n",posix_getpid());
    fprintf(STDOUT,"child process:xpid=%d,text=%s\n",$pid,$text);
}else{

    //父进程
    sleep(2);//目的是让子进程先运行，父进程先睡眠
    fprintf(STDOUT,"pid=%d 我是父进程，我睡了2秒才打印的.\n",posix_getpid());
    fprintf(STDOUT,"father process:xpid=%d,text=%s\n",$pid,$text);
}
fprintf(STDOUT,"pid =%d,ppid=%d,run here.\n",posix_getpid(),posix_getppid());