<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/12 0012
 * Time: 下午 7:36
 */
// 系统V IPC 消息队列 [消息队列，信号量，共享存储] posix IPC
// 消息队列：实际就是一个队列，同样由操作系统内核来维护，使用msgget来创建一个消息队列，创建成功返回队列的ID
// 键key【对应内部数据结构的标识符ID】
// ipc 权限
// php 的函数实际上是封装c函数库[libc,glibc] 以及一些系统调用函数
// 1)strace
// 2)php-src
//
//class msq_queue{
//
//}
//struct msg_queue {
//    struct kern_ipc_perm q_perm;
//	time64_t q_stime;		/* last msgsnd time */
//	time64_t q_rtime;		/* last msgrcv time */
//	time64_t q_ctime;		/* last change time */
//	unsigned long q_cbytes;		/* current number of bytes on queue */
//	unsigned long q_qnum;		/* number of messages in queue */
//	unsigned long q_qbytes;		/* max number of bytes on queue */
//	struct pid *q_lspid;		/* pid of last msgsnd */
//	struct pid *q_lrpid;		/* last receive pid */
//
//	struct list_head q_messages;
//	struct list_head q_receivers;
//	struct list_head q_senders;
//} __randomize_layout;

$key = ftok("demo42.php","x");//就是把文件与id转换为一个key 它是根据inode
$msqid = msg_get_queue($key);//msgget 返回队列ID
// 第4个参数跟序列化有关，php内部实现的时候，它会把message进行序列化处理，然后再调用系统调用 msgsnd(msqid,序列化的数据)
echo msg_send($msqid,2,"hello",false);//msgsnd
echo $msqid;