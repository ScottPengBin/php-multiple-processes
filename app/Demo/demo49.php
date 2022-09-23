<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/14 0014
 * Time: 下午 10:07
 */
//以下代码是父子血缘进程间间的通信演示
$key = ftok("demo48.php","x");
$shm_id = shm_attach($key,128);

//$pid = pcntl_fork();
//
//if ($pid==0){
//
//    $data = shm_get_var($shm_id,1);
//    fprintf(STDOUT,"pid=%d read :%s\n",posix_getpid(),$data);
//    exit(0);
//}

shm_put_var($shm_id,1,"hello");

pcntl_wait($status);

