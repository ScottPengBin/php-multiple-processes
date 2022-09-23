<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/14 0014
 * Time: 下午 10:10
 */
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

echo shm_get_var($shm_id,1);


