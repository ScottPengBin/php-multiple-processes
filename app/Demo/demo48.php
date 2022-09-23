<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/14 0014
 * Time: 下午 9:22
 */
//共享内存
// 系统V IPC 消息队列，信号量，共享存储
// 共享存储实际上就是系统会开辟一块存储空间，进程会使用相关函数shmget来映射【连接】到进程的地址空间virtual space address
// 进程1，进程2
// 内存：内存分配出来的是一块连续的存储空间【在分分配存储空间的时候可以指定空间大小，一般按页分配4096,8192]
// 对内存进行读写操作时，一定要指定写入的位置
// 对内存的操作是非常复杂的【c/c++，malloc,free,new,delete】

// php的实现【它封装了shm开头的函数以及Shared Memory 函数】但是内部函数一模一样
// socket stream ...
$key = ftok("demo48.php","x");
echo $shm_id = shm_attach($key,128);//10bytes 1bytes=8bit  //shm_attach shmget shmat
//shmat 表示将创建好的共享存储区域关联到进程的地址空间
//char 1byte int 4bytes|8bytes

//shm_id 它对应自己的地址空间【当前进程】实际上它是映射【连接】了系统分配的共享存储区域
echo shm_put_var($shm_id,1,"a");
echo shm_get_var($shm_id,1);


shm_remove($shm_id);//shmctl