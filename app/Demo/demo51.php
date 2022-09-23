<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/14 0014
 * Time: 下午 10:12
 */

$key = ftok("demo48.php","x");
$shm_id = shmop_open($key,"c",0666,128);
//内存块：实际上它是一块连续的存储空间
//128
echo shmop_write($shm_id,"hello",0);