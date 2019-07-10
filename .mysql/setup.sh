#!/bin/bash
set -e

echo '启动mysql....'
# 启动mysql
service mysql start
sleep 3

echo '开始导入数据....'
# 导入数据
mysql < /mysql/hyperf.sql
sleep 3

echo 'mysql容器启动完毕,且数据导入成功'

tail -f /dev/null