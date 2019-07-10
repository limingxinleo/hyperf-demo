
## 客户端

### 部署

#### 1. 安装swoole_tracker依赖包
在命令行中执行 `./deploy_env.sh 127.0.0.1`。(127.0.0.1为swoole_admin安装的机器IP，如果您是试用用户，请登录[后台](https://www.swoole-cloud.com/dashboard/catdemo)查看服务端地址IP，并将127.0.0.1改为查看到的ip。ps：无需带端口号)


#### 2. 安装对应的`swoole_tracker`扩展

1. 解压后根目录会有多个版本swoole_tracker.so文件，这些是已经编译好的swoole_tracker扩展，后缀的数字是您所准备安装机器中php的版本，例如70对应7.0，71对应7.1版本。
2. 通过`php -v`查看您的php版本，通过`php -ini | grep extension`查看扩展安装目录
3. 将对应的版本的扩展文件放入您的php扩展安装目录中，并去掉后缀数字。例：`cp ./swoole_tracker70.so /usr/local/php/lib/php/extensions/no-debug-non-zts-20170718/swoole_tracker.so`
4. 然后在php.ini中需增加以下配置项目，可通过`php --ini`查看配置文件路径

```ini
extension=swoole_tracker.so 
apm.enable=1           #打开总开关
apm.sampling_rate=100  #采样率 例如：100%

# 手动埋点时再添加
apm.enable_memcheck=1  #开启内存泄漏检测 默认0 关闭
```

#### 3. 卸载xdebug扩展 

如果安装了xdebug，请将其在配置文件中注释掉，因为xdebug会影响`swoole_tracker`扩展，致使数据上报失败。

#### 4. 如果您是swoole用户请重启您的swoole server，如果您是fpm用户，请重启您的php-fpm

#### 试用文档

https://www.yuque.com/swoole-wiki/try

#### 详细使用文档

https://www.yuque.com/swoole-wiki/dam5n7