<?php


namespace App\Base;

use Event;
use EventBase;
use Exception;

/**
 * @className Base
 * @nameSpace App\Base
 * @time      2022/9/20 10:54
 * @user      scott
 */
class Base
{

    protected static $file = '';

    protected static $allPid = [];


    /**
     * Event base.
     * @var object
     */
    protected static $_eventBase = null;


    /**
     * @throws Exception
     */
    public function __construct()
    {
        self::$file = '/tmp/pb.sock';
        if (file_exists(static::$file)) {
            unlink(static::$file);
        }
        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);

        socket_bind($socket, self::$file);

        self::$_eventBase = new EventBase();
        cli_set_process_title("pb: master process ");
        //$this->daemonize();
        $this->signal();

        $num = 1;
        static::$allPid[] = posix_getpid();


        for ($i = 1; $i <= $num; $i++) {
            $pid = pcntl_fork();
            if ($pid > 0) {
                // todo
            } elseif ($pid == 0) {
                static::$allPid[] = posix_getpid();
                cli_set_process_title("pb: work process " . $i);

                $event = new Event(self::$_eventBase, $socket, Event::READ | Event::PERSIST, function ($socket) {
                    socket_listen($socket, 1024);
                    $connFd = socket_accept($socket);
                    $data = socket_read($connFd, 1024);
                    if ($data) {
                        sleep(1);
                        $pid = posix_getpid();
                        fprintf(STDOUT, "recv from client:%s\n", $data);
                        fprintf(STDOUT, "recv from pid:%d\n", $pid);
                    }
                });
                $event->add();
                $this->signal();
                self::$_eventBase->loop();
            } else {
                die('fork失败');
            }
        }

        while (true) {
            \pcntl_signal_dispatch();
            // Suspends execution of the current process until a child has exited, or until a signal is delivered
            $status = 0;
            $pid = \pcntl_wait($status, \WUNTRACED);
            // Calls signal handlers for pending signals again.
            \pcntl_signal_dispatch();

            echo $pid;
        }
    }


    public function signal()
    {
        $signals = [\SIGINT, \SIGTERM, \SIGHUP, \SIGTSTP, \SIGQUIT, \SIGUSR1, \SIGUSR2, \SIGIOT, \SIGIO];
        foreach ($signals as $signal) {
            Event::signal(self::$_eventBase, $signal, 'signalHandler');
            //pcntl_signal($signal, [self::class, 'signalHandler']);
        }
    }


    public function daemonize()
    {
        $pid = pcntl_fork();
        if (-1 === $pid) {
            exit('Fork fail');
        } elseif ($pid > 0) {
            exit(0);
        }
        if (-1 === posix_setsid()) {
            exit("Setsid fail");
        }
        cli_set_process_title("pb: master process");
    }


    public static function signalHandler($signo)
    {
        fprintf(STDOUT, "pid=%d,我接收到一个信号:%d\n", getmypid(), $signo);
        foreach (self::$allPid as $pid) {
            var_dump('kill-' . $pid);
            posix_kill($pid, SIGKILL);
        }
        if (file_exists(static::$file)) {
            unlink(static::$file);
        }

        static::$_eventBase->stop();
    }
}