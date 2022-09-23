<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/14 0014
 * Time: 下午 10:16
 */
$key = ftok("demo48.php","x");
$shm_id = shmop_open($key,"c",0666,128);
//内存块：实际上它是一块连续的存储空间

echo shmop_close($shm_id);// 它的原理：是把共享内存段与进程的地址空间映射关系给断开
//128
echo shmop_read($shm_id,0,5);
