<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/13 0013
 * Time: 下午 9:20
 */
$file = "demo47.txt";
$count=0;
file_put_contents($file,$count);
$key = ftok("demo47.php","x");
$sem_id = sem_get($key,1);//把它当作二值信号量来用 semget
// 光看不练，光问不练，光讲不练，刻意的去练习，刻意的去搜索资料，才有更多的收入

$pid = pcntl_fork();
// 如果这些语句翻译成汇编语句【机器指令】
if ($pid==0){

    sem_acquire($sem_id);//semop
    $x = (int)file_get_contents($file);
    for ($i=0;$i<1000;$i++){

        $x+=1;

    }
    file_put_contents($file,$x);
    sem_release($sem_id);
    //这条语句其实对应的机器指令不可能是一条，是多条，可能指令只执行一半，就被其它进程打断
    //数据就破坏了
    exit(0);
}
sem_acquire($sem_id);
$x = (int)file_get_contents($file);

for ($i=0;$i<1000;$i++){

    $x+=1;

}
// 多进程写文件，多文件，下载东西
file_put_contents($file,$x);
sem_release($sem_id);

//echo file_get_contents($file);