FROM hyperf/hyperf:7.2-alpine-cli

WORKDIR /opt
# 部署node-agent
ADD . /opt

RUN ./deploy_env.sh 118.25.177.99 \
    && printf '#!/bin/sh\n/opt/swoole/script/php/swoole_php /opt/swoole/node-agent/src/node.php &\nphp-fpm $@' > /opt/swoole/entrypoint.sh \
    && chmod 755 /opt/swoole/entrypoint.sh \
    && cp /opt/swoole-tracker.ini /etc/php7/conf.d/swoole-tracker.ini \
    && php -m

# 启用entrypoint脚本（-x方便调试， 可以去掉）
ENTRYPOINT [ "sh", "-x", "/opt/swoole/entrypoint.sh" ]