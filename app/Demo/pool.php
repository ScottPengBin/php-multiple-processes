<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/17 0017
 * Time: 下午 8:10
 */
namespace MsqServer;
// 中断系统调用：就是当程序正在执行系统调用 【select】收到中断信号之后，就会中断不在执行，同时报错
// 中断信号|多进程|IPC消息队列|unix域套接字 socket_pair
class Process
{
    public $_pid;
    public $_msqid;
}

class Server
{
    public $_sockFile = "pool.sock";
    public $_processNum=3;
    public $_keyFile="pool.php";
    public $_idx;
    public $_process = [];
    public $_sockfd;
    public $_run=true;
    public $_roll=0;

    public function sigHandler($signo)
    {
        $this->_run=false;
    }
    public function __construct($num=3)
    {
        $this->_processNum = $num;
        pcntl_signal(SIGINT,[$this,"sigHandler"]);//ctrl+c
        $this->forkWorker();
        $this->Listen();

        $exitPid = [];
        while(1){

            $pid = pcntl_wait($status);//回收子进程
            if ($pid>0){
                $exitPid[] = $pid;
            }
            if(count($exitPid==$this->_processNum)){
                break;
            }
        }
        foreach ($this->_process as $p){

            msg_remove_queue($p->_msqid);
        }
        fprintf(STDOUT,"master shutdown\n");

    }

    public function forkWorker()
    {

        $processObj = new Process();
        for($i=0;$i<$this->_processNum;$i++){

            $key = ftok($this->_keyFile,$i);
            $msqid = msg_get_queue($key);
            $process = clone $processObj;
            $process->_msqid = $msqid;

            $this->_process[$i] = $process;
            $this->_idx = $i;

            $this->_process[$i]->_pid = pcntl_fork();
            if ($this->_process[$i]->_pid==0){

                $this->worker();
            }else{
                continue;
            }
        }


    }
    public function Listen()
    {

        $this->_sockfd = socket_create(AF_UNIX,SOCK_STREAM,0);
        if (!is_resource($this->_sockfd)){

            fprintf(STDOUT,"socket create fail:%s\n",socket_strerror(socket_last_error($this->_sockfd)));

        }
        unlink($this->_sockFile);

        if (!socket_bind($this->_sockfd,$this->_sockFile)){

            fprintf(STDOUT,"socket bind fail:%s\n",socket_strerror(socket_last_error($this->_sockfd)));

        }

        socket_listen($this->_sockfd,10);

        $this->eventLoop();

    }

    public function selectWorker($data)
    {

        /** @var Process $process */
        $process = $this->_process[$this->_roll++%$this->_processNum];
        $msqid = $process->_msqid;
        if (msg_send($msqid,1,$data,true,false)){

            fprintf(STDOUT,"master send ok\n");

        }
    }
    public function eventLoop()
    {
        $readFds = [$this->_sockfd];
        $wrteFds = [];
        $exFds = [];
        while ($this->_run){

            pcntl_signal_dispatch();
            //select I/O 复用函数
            $ret = socket_select($readFds,$wrteFds,$exFds,NULL,NULL);
            if (FALSE===$ret){
                break;
            }
            else if ($ret===0){
                continue;
            }

            if ($readFds){

                foreach ($readFds as $fd){

                    if ($fd==$this->_sockfd){

                        $connfd = socket_accept($fd);
                        $data = socket_read($connfd,1024);
                        if ($data){
                            $this->selectWorker($data);
                        }
                        socket_write($connfd,"ok",2);
                        socket_close($connfd);
                    }
                }
            }
        }
        socket_close($this->_sockfd);

        foreach ($this->_process as $p){

            if (msg_send($p->_msqid,1,"quit")){

                fprintf(STDOUT,"master send quit ok\n");

            }
        }
    }

    public function worker()
    {
        fprintf(STDOUT,"child pid=%d start\n",posix_getpid());

        /** @var Process $process */
        $process = $this->_process[$this->_idx];
        $msqid = $process->_msqid;

        while (1){

            if (msg_receive($msqid,0,$msgType,1024,$msg)){


                fprintf(STDOUT,"child pid=%d recv:%s\n",posix_getpid(),$msg);

                if (strncasecmp($msg,"quit",4)==0){
                    break;
                }
            }
        }
        fprintf(STDOUT,"child pid=%d shutdown\n",posix_getpid());

        exit(0);
    }
}


(new Server(3));