<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/15 0015
 * Time: 下午 7:52
 */
// unix域套接字网络进程间通信
// 1）管道 posix_mkfifo 【它调用的系统是mkfifo】
// php解释器它在运行的时候，php自己封装的函数posix_mkfifo它内部调用的系统函数是哪一个 strace
// python解释器，每个编程语言封装的函数【就是开发人员使用的函数】不一样，但是系统调用是一样的
// 2) 系统V IPC 消息队列，信号量，共享内存
// 3) 套接字  UNIX域【IPV4,IPV6域通信】

// 套接字：socket 实际上呢是一种连接，套接字的通信域【协议】类型：ipv4[AF_INET],ipv6[AF_INET6]
// UNIX[AF_UNIX,AF_LOCAL] unix域也叫本地域【进程间通信的时候不需要通过网卡，不通过网络】
// ipv4,ipv6通信要经过网络的【网卡】数据到达网卡时是一种数据帧 frame 【目标网络物理地址|源物理地址|...|数据】
// unix 通过时不经过网络

// 套接字类型：TCP,UDP transmission control protocol user datagram protocol
// tcp:需要连接【三次握手】，是可靠的，重传，有序的，字节流服务
// udp:不需要连接，不可靠的，数据长度是固定的，数据报服务
// raw:
// unix域/ipv/ipv6：tcp/upd

// PS:unix域的udp是可靠的
// ipv4,ipv6套接字作为服务进程的时候是要绑定ip,port【以便寻址，ip主要是用于确定进程间通信的时候知道网络上的确定的机器，
// port用于确定进程间通信时，确定到底是哪个进程】
// bind 地址 命名
// unix套接字作为命名unix域套接字时一定要绑定地址【它的地址比较特殊是一个文件，socket文件】
// 1) 无命名的【创建好的unix域套接字不需要绑定地址bind】全双工
// 2) 命名unix域套接字

// stream,socket  [低层的系统调用还是socketpair]
// 我一般不太喜欢遵守官方手册【特别是脚本语言】
//$fd = stream_socket_pair(STREAM_PF_UNIX,STREAM_SOCK_STREAM,0 );
$fd = [];
socket_create_pair(AF_UNIX,SOCK_STREAM,0,$fd);

print_r($fd);




