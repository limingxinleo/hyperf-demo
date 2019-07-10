#!/opt/swoole/script/php/swoole_php
<?php
$stderr = fopen('php://stderr', 'w');
function killall($target){
    global $stderr;
    exec("kill -15 " . $target);
    sleep(1);
    $things = scandir("/proc");
    if(NULL == $things){
        fprintf($stderr,"cant ls procfs\n");
        exit(1);
    }
    $ret = [];
    foreach ($things as $dir){
        if (!is_numeric($dir)) continue;
        $data = @file_get_contents("/proc/$dir/status");
        if(NULL!=$data && preg_match('/PPid\s*:\s*([0-9]+)/', $data, $match) > 0 && $match[1] == $target){
            array_push($ret, $dir);
        }
    }
    if(count($ret)>0){
        fprintf($stderr,"process remaining send SIGKILL\n");
        exec("kill -9 " . implode(" ", $ret));
        exec("kill -9 " . $target);
    }
}
if(count($argv)>1){
    killall($argv[1]);
}else{
    fprintf($stderr,"no target given\n");
    exit(22);
}
