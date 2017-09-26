<?php
/**
 * Created by PhpStorm.
 * User: usr0301564
 * Date: 2017/09/26
 * Time: 10:52
 */
class ProcLoop
{
    private $procs = array();
    private $pipes = array();
    private $stdoutCallbacks = array();
    private $stderrCallbacks = array();
    private static $fdSpecs = array(
        1 => array('pipe', 'w'),
        2 => array('pipe', 'w'),
    );

    public function addProc($cmd, $stdoutCallback, $stderrCallback)
    {
        $this->procs[] = proc_open($cmd, static::$fdSpecs, $pipes);
        stream_set_blocking($pipes[1], 0);
        stream_set_blocking($pipes[2], 0);
        $this->pipes[] = array($pipes[1], $pipes[2]);
        $this->stdoutCallbacks[] = $stdoutCallback;
        $this->stderrCallbacks[] = $stderrCallback;
    }

    public function run()
    {
        $count = count($this->procs);
        while ($this->isAnyPipeAvailable()) {
            for ($i = 0; $i < $count; $i++) {
                $tmp = $this->pipes[$i];
                $write = NULL;
                $except = NULL;
                $ret = stream_select($tmp, $write, $except, 1);
                if ($ret === false) {
                    throw new RuntimeException;
                } else if ($ret !== 0) {
                    foreach ($this->pipes[$i] as $sock) {
                        if ($buf = fread($sock, 4096)) {
                            if ($buf) {
                                if ($sock === $this->pipes[$i][0]) {
                                    call_user_func($this->stdoutCallbacks[$i], $buf);
                                } else {
                                    call_user_func($this->stderrCallbacks[$i], $buf);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function isAnyPipeAvailable()
    {
        foreach ($this->pipes as $pipe) {
            if (feof($pipe[0]) === false || feof($pipe[1]) === false) {
                return true;
            }
        }
        return false;
    }
}

$loop = new ProcLoop;

$echoStdout = function ($buf) {
    echo "\033[34m[STDOUT]\033[0m " . $buf;
};

$echoStderr = function ($buf) {
    echo "\033[31m[STDERR]\033[0m " . $buf;
};

echo 'Start!' . "\n";
$time_start = microtime(true);

$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 0', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 1', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 2', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 3', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 4', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 5', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 6', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 7', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 8', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 9', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 10', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 11', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 12', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 13', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 14', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 15', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 16', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 17', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 18', $echoStdout, $echoStderr);
$loop->addProc('/Users/usr0301564/gmo-am/php/parse.php 19', $echoStdout, $echoStderr);
$loop->run();
$time = (microtime(true) - $time_start);
echo "{$time} 秒" . "\n";
echo 'Done!' . "\n";
