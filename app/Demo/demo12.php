<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/2 0002
 * Time: 下午 3:52
 */
// 1) SUID,SGID
// set user ID,set group ID 设置用户ID,设置组ID
// 设置了set user ID的程序就是一个特权程序了，启动之后就是一个特权进程


// 当特殊标志's'这个字符出现文件拥有者的x权限位的时候就叫 set UID,简称SUID，或是叫SUID特殊权限

// 2) SUID,SGID 它的用途是什么 呢？
// 一般来说，以root启动的程序都是超级进程，一般来说是一些重要的服务程序
// 有时候我们经常是以普通用户来执行程序的，www
// 但有时候普通进程需要访问一些特殊的资源，这个时候我们就需要提升权限来访问的

//3 ） 如何设置SUID  就在可执行文件的权限x位上设置 chmod u/g/o + s elf file


// linux etc/shadow 普通用户是无法查看，修改，删除 rw
// 但是 root 可以
// 普通用户[jack,csm] 可以通过/bin/passwd这个ELF可执行文件修改 /etc/shadow文件的

// 特权进程就能访问系统中的资源了

$file = "pwd.txt";
$uid = posix_getuid();//csm 1001 UID
$euid = posix_geteuid();//csm 1001

fprintf(STDOUT,"uid=%d,euid=%d\n",$uid,$euid);

//这样设置是不行的
posix_setuid($euid);
posix_seteuid($euid);// 设置SUID 它就是超级用户 0

// 提权  [修改权限以便能访问特殊资源]
$uid = posix_getuid();//csm 1001 UID
$euid = posix_geteuid();//csm 1001

fprintf(STDOUT,"uid=%d,euid=%d\n",$uid,$euid);

if (posix_access($file,POSIX_W_OK)){

    fprintf(STDOUT,"我能修改...\n");
    $fd = fopen($file,"a");
    fwrite($fd,"php is the best!");
    fclose($fd);


}else{
    fprintf(STDOUT,"我不能修改此文件...\n");
}

// 平常开发程序一般是能普通用户启动的进程，它是无法访问需要权限的资源的
// 所以我们通过 SUID 提权以便能访问需要权限的资源

// 注意：提权访问完后，一定要改回来
// 在编写特权进程时，提权访问资源之后一定要改回来。