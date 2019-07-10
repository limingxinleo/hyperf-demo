<?php
namespace Sdk;
date_default_timezone_set('Asia/Shanghai');

class Monitor
{
    public static function report($data)
    {
        $da = @\swoole_serialize::unpack($data);
        if(!is_array($da)){//反序列化失败
            return false;
        }
        $traceDir = '/tmp/swoole/trace';
        $data = $data.'#swooletrace#';
        $time = time();
        $second = date('s',$time)<=30?'30':'60';
        $traceFile = $traceDir."/trace_".date("YmdHi",$time).'_'.$second;//半分钟一个文件
        $dir = dirname($traceFile);
        umask(0);
        if (!is_dir($dir))
        {
            mkdir($dir, 0777, true);
        }
        //检查删除15分钟之前的数据
        if(!is_file($traceFile)){
            $filesArr = array();
            if(@$handle = opendir($traceDir)) { //注意这里要加一个@，不然会有warning错误提示：）
                while(($file = readdir($handle)) !== false) {
                    if($file != ".." && $file != ".") { //排除根目录；
                        $filesArr[] = $file;
                    }
                }
                closedir($handle);
            }
            if($filesArr){
                foreach ($filesArr as $key => $value) {
                    $nameArr = explode('_',$value);
                    if(isset($nameArr[1]) && $nameArr[1] < date("YmdHi",$time-60*15)){
                        unlink($traceDir.'/'.$value);
                    }
                }
            }
        }
        $fp = fopen($traceFile, 'a+');
        //打开文件失败
        if (!$fp)
        {
            return false;
        }
        $length = strlen($data);
        for ($written = 0; $written < $length; $written += $fwrite)
        {
            $fwrite = fwrite($fp, substr($data, $written));
            //写文件失败了
            if ($fwrite === false)
            {
                fclose($fp);
                return false;
            }
        }
        fclose($fp);
        //必须为777
        @chmod($traceFile, 0777);
        return true;
    }

    static function export($data)
    {
        echo date("Y-m-d H:i:s")." ".var_export($data,1);
    }

}
