<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/13 0013
 * Time: 下午 7:35
 */
$key = ftok("demo42.php","x");
$msqid = msg_get_queue($key);
// 当前消息队列的状态
print_r(msg_stat_queue($msqid));//msgctl IPC_STAT 来获取消息队列当前的状态信息【权限，当前队列中消息个数】

$pid = pcntl_fork();
if ($pid==0){
    $i=1;
    while (1){
        //MSG_IPC_WAIT【启用非阻塞】 msgrcv 该系统调用函数将立马返回
        //strace
        //使用阻塞与非阻塞：msgrcv 如果使用非阻塞方式，该函数调用的次数非常高，所以占用cpu资源就高
        //阻塞方式：调用次数就比较低，因为必须等消息队列有数据为止才返回
        $ret =  msg_receive($msqid,0,$msgType,1024,$msg,true,MSG_IPC_NOWAIT,$error);
        if ($error!=MSG_ENOMSG){
            echo $msg."\r\n";
        }

//        if ($i++==3){
//
//            break;
//        }
        // 一堆业务逻辑
        //echo "";
    }
    exit(0);
}
// 多进程控制：大家要知道每个进程代码的运行范围，还要知道fork之后，进程复制的数据是什么
$i=1;
while (1){
    //消息队列是有限制的
    echo msg_send($msqid,2,"hello",true,true);//msgsnd
    sleep(1);

    if ($i++==3){
        posix_kill($pid,SIGKILL);
        break;
    }
}


$pid = pcntl_wait($status);
if ($pid>0){

    fprintf(STDOUT,"ext pid=%d\n",$pid);
}


if (msg_remove_queue($msqid)){//msgctl IPC_RMID
    echo "remove ok\n";
}

