<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/7 0007
 * Time: 下午 3:11
 */
// 守护进程
// 终端，进程组，会话有着密切的关系
// 守护进程一般运行在后台，并且没有控制终端，同时守护进程是一直运行的
// 进程启动之后，进程在内存的数据会写入到proc目录中

// 编写守护进程有以下几点要求
// 1) 设置文件创建屏蔽字 umask(0)
// 2) 一般父进程先fork一个子进程，然后父进程exit,子进程调用setsid函数来创建会话
// 如果调用setsid的进程是组长进程就会报错
// 僵尸进程  孤儿进程  组长进程 会话首进程  控制进程
// bin/bash php demo28.php clone --->execve(php [demo28.php])
// 进程调用setsid之后
//  a 该进程会变成组长进程 b 该进程会变成会话首进程 c该进程不再有控制终端

// 当调用setsid之后，一般会再创建一个子进程，让会话首进程退出,确保该进程不会再获得控制终端
// unix/linux 它的发行版本有BSD,System V

// 3 ) 把守护进程的工作目录working directory 设置为根目录
// 4) 把一些文件描述符关闭 [标准输入，标准输出，标准错误]
// fopen("/dev/null") [dev/null这一个空设备文件，可以看作是个黑洞文件，对该文件的任何读写错误数据都会被丢弃掉]
// 一般喜欢把/dev/null 代替0,1,2
// echo "hello" write(1,"hello")

//0
umask(0);

//1
$pid = pcntl_fork();
if ($pid>0){
    exit(0);
}
//2
// 该进程会变成组长进程，会话首进程，没有控制终端TTY ?
if (-1==posix_setsid()){
    fprintf(STDOUT,"setsid 失败\n");
}

$pid = pcntl_fork();
if ($pid>0){
    exit(0);//让会话首进程退出
}
//3 working directory
chdir("/");

//4 0 1 2 STDIN STDOUT STDERR
fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);

//file_put_contents fopen fwrite fread
// 当关掉以上标准输入，标准输出，标准错误文件之后，如果后面要对文件的操作[比如创建一个文件，写文件等]
// 它返回的文件描述符就从0开始

// 这里用dev/null来代替标准输入，标准输出，标准错误[把键盘，显示器当作黑洞]
$stdin= fopen("/dev/null","a"); //0
$stdout = fopen("/dev/null","a"); //1
$stderr = fopen("/dev/null","a"); //2

echo "hello.x";//write(1,"hello.x")

//file_put_contents("/home/process/demo28.log","pid=".posix_getpid());
$fd = fopen("/home/process/demo28.log","a");//1

//fclose($fd);


//write(0,"pid=xx");
// web [nginx,apache] mysql 
$pid = pcntl_fork();

if ($pid==0){

    fprintf($fd,"pid=%d,ppid=%d,sid=%d,time=%s\n",posix_getpid(),posix_getppid(),posix_getsid(posix_getpid()),time());
    while (1){
        sleep(1);
    }
    exit(0);
}

$pid = pcntl_wait($status);
if ($pid>0){

    fprintf($fd,"exit pid=%d,ppid=%d,sid=%d,time=%s\n",$pid,posix_getppid(),posix_getsid(posix_getpid()),time());
    fclose($fd);
    exit(0);

}




