安装
-----
* swoole 4.2.8 或更高版本
* swoole/framework 最新版本，请将 framework 目录配置加到 php.ini 的 include_path 配置项中

```shell
vim php.ini
include_path=.:/usr/share/php:/path/to/swoole/framework 
```

运行
----
```shell

cd node-agent/tests
nohup php sys.php 10 &
```