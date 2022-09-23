<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/7 0007
 * Time: 下午 5:07
 */
// 守护进程
// bin/bash

// 在bin/bash进程下启动的命令一般称为作业｜工作
// 前景｜前台：一般受ctrl+c等指令影响
// 背景｜后台：不受ctrl+c等指令影响

// fg/bg
//1 ) & 可以把作业丢到背景中执行
// 2) jobs 可以列出背景中的作业
// 3) ctrl+z 可以将命令丢到背景中暂停
// 4) bg 让背景中的作业执行

// 作业会随着bin/bash shell的关闭而退出

// 1 把这个程序改为守护进程
// 2 nohup & 就可以把让进程与控制终端断开成为守护进程

echo posix_getpid();


while (1){

    sleep(2);
}