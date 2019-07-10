#!/bin/sh
# swoole dasboard installer rewritten by dixyes

# for zsh compatiable
[ -n "$ZSH_VERSION" ] && emulate -L ksh;

# log utils
logi(){
    printf "[0;1;32m[IFO][0;1m $*[0m\n"
}
logw(){
    printf "[0;1;33m[WRN][0;1m $*[0m\n"
}
loge(){
    printf "[0;1;31m[ERR][0;1m $*[0m\n"
}

logi "Swoole Dashboard NodeAgent installer"

[ "`id -u 2>&1`"x != '0x' ] && loge "This install script should be run as root." && exit 13 # 13 for EACCES

# this regex will match 99.999.9999.9999 or something like this, however, sometimes you can bind such a string as domain.(i.e. link in docker)
[ "`echo $1 | grep -E '^([a-zA-Z0-9][a-zA-Z0-9\-]*?\.)*[a-zA-Z0-9][a-zA-Z0-9\-]*?\.?$'`"x = "x" ] &&
logi "Usage: $0 <remote>\n\twhich \"remote\" should be ipv4 address or domain of swoole-admin host\n" && exit 22 # 22 for EINVAL

# install files
logi 'Start Installing node-agent files'
mkdir -p /opt/swoole/node-agent /opt/swoole/logs /opt/swoole/public /opt/swoole/config 2>&-
chmod 777 /opt/swoole/logs
logi ' Installing files at /opt/swoole/node-agent'
rm -rf /opt/swoole/script
cp -rf ./app_deps/node-agent/* /opt/swoole/node-agent/
ln -s /opt/swoole/node-agent/script/ /opt/swoole/script
chmod +x /opt/swoole/script/*.sh
chmod +x /opt/swoole/script/php/swoole_php
logi ' Installing files at /opt/swoole/public'
cp -rf ./app_deps/public/* /opt/swoole/public/

# clean cache
logi 'Clean caches at /tmp/mostats'
rm -rf /tmp/mostats

# backup config file: yet no use.
logi 'Backing up config file at /opt/swoole/config to /tmp/swconfigback'
if [ -d "/opt/swoole/config/" ];then
    cp -rf /opt/swoole/config /tmp/swconfigback
    rm -rf /opt/swoole/config
    mkdir /opt/swoole/config
fi
chmod 777 /opt/swoole/config
chmod 777 /opt/swoole

# remove legacy system-side supervisor nodeagent
if [ -f /etc/supervisor/conf.d/node-agent.conf ]
then
    logw 'Removing legacy system-wide supervisor files for nodeagent.'
    supervisorctl -c /etc/supervisor/supervisord.conf stop node-agent >>/tmp/na_installer.log 2>&1
    rm -f /etc/supervisor/conf.d/node-agent.conf >>/tmp/na_installer.log 2>&1
    supervisorctl -c /etc/supervisor/supervisord.conf update >>/tmp/na_installer.log 2>&1
fi

# remove legacy supervisor in /opt/swoole/pysandbox
if [ -e /opt/swoole/pysandbox ]
then
    logw 'Removing legacy supervisor files at /opt/swoole'
    if [ -S /opt/swoole/supervisor/supervisor.sock ]
    then
        logw ' Stopping legacy python venv supervisor'
        /opt/swoole/pysandbox/bin/supervisorctl -c /opt/swoole/supervisor/supervisord.conf shutdown
    fi
    rm -rf /opt/swoole/pysandbox
    rm -rf /opt/swoole/supervisor
fi

# (Deprecated) use this to disable supervisor check
#logi "Workaround for supervisor dir check"
#echo "{\"supervisor\":{\"config_dir\":[\"/opt/swoole/config\"]}}" > /opt/swoole/config/config.json
mv /opt/swoole/node-agent/userid /opt/swoole/config/ >/tmp/na_installer.log 2>&1

if type nmap ss kill ps >>/tmp/na_installer.log 2>&1
then
    logi 'All dependencies are ok, skipping dependencies installion.'
else
    # find package manager ,then install dependencies
    #  use varibles for future use.(may be removed if it takes no use at all.)
    # TODO: dnf, yast
    if type apt-get >>/tmp/na_installer.log 2>&1
    then
        logi 'super moo power detected, using apt-get to install dependencies.'
        PACMAN=apt-get
        logi ' Updating apt cache.'
        $PACMAN update -y >>/tmp/na_installer.log 2>&1
        PACMAN_INSTALL="${PACMAN} install -y --no-install-recommends "
        type nmap ps kill >>/tmp/na_installer.log 2>&1 || {
            logi ' Installing nmap and procps for commands: nmap, ps, kill.'
            $PACMAN_INSTALL nmap procps >>/tmp/na_installer.log 2>&1
        }
        # iproute2 package name is different in different distros, so install it in two ways.
        type ss >>/tmp/na_installer.log 2>&1 || {
            logi ' Installing iproute for command: ss.'
            $PACMAN_INSTALL iproute >>/tmp/na_installer.log 2>&1 ||
            $PACMAN_INSTALL iproute2 >>/tmp/na_installer.log 2>&1
        }
    elif type yum >>/tmp/na_installer.log 2>&1
    then
        logi 'yellow dog detected, using yum to install dependencies.'
        PACMAN=yum
        logi ' Updating yum cache.'
        $PACMAN makecache >>/tmp/na_installer.log 2>&1
        PACMAN_INSTALL="${PACMAN} install -y "
        logi ' Installing nmap and iproute for commands: nmap, ss.'
        # rpm distros' coreutils have ps, kill things in it, we needn't expclit install it : when script go here, it's installed
        deps="nmap iproute"
        # due to droping supervisor denpendency, we needn't epel any more.
        $PACMAN_INSTALL $deps >>/tmp/na_installer.log 2>&1
    elif type apk >>/tmp/na_installer.log 2>&1
    then
        logi 'coffe-making-able package manager detected, using apk to install dependencies.'
        PACMAN=apk
        logi ' Updating apk cache.'
        $PACMAN update >>/tmp/na_installer.log 2>&1
        PACMAN_INSTALL="${PACMAN} add -q "
        # alpine's busybox have ps kill things in it, we needn't install coreutils.
        type nmap >>/tmp/na_installer.log 2>&1 || {
            logi ' Installing nmap for command: nmap.'
            $PACMAN_INSTALL nmap >>/tmp/na_installer.log 2>&1
        }
        # ldd is for determining libc type.
        type ldd >>/tmp/na_installer.log 2>&1 || {
            logi ' Installing libc-utils for command: ldd.' &&
            $PACMAN_INSTALL libc-utils >>/tmp/na_installer.log 2>&1
        }
        type ss >>/tmp/na_installer.log 2>&1 || {
            logi ' Installing iproute2 for command: ss.' &&
            $PACMAN_INSTALL iproute2 >>/tmp/na_installer.log 2>&1
        }
    fi
fi

# musl workaround TODO: use AWSL rebuild binaries.
if [ "`ldd 2>&1 | grep -i musl`x" != "x" ]
then
    logw 'You are using musl as libc, assuming you are using alpine 3+, preparing dynamic libraries for running swoole_php.'
    # TODO:assuming using alpine, will be modified to work with all musl distros.
    apk add --allow-untrusted musl-compat/glibc-2.29-r0.apk >>/tmp/na_installer.log 2>&1
    cp musl-compat/*.so.* /usr/glibc-compat/lib
fi

# if something still not installed ,error out
lack_deps=""
type nmap >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} nmap,"
type ss >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} ss(from iproute or iproute2),"
type kill >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} kill(from coreutils or psproc(at debian variants)),"
type ps >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} ps(from coreutils or psproc(at debian variants)),"
type df >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} df(from coreutils),"
type rm >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} rm(from coreutils),"
type cat >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} cat(from coreutils),"
type chmod >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} chmod(from coreutils),"
# due to drop dependency of supervisor we needn't python things now.
#type easy_install 1>&- 2>&- || lack_deps="${lack_deps} python2-setuptools" # #using virtualenv, we neend't this
#python2 -V >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} python2"
#python -m virtualenv --version >>/tmp/na_installer.log 2>&1 || lack_deps="${lack_deps} virtualenv"
[ "x" != "x$lack_deps" ] && loge "for command(s) ${lack_deps%,}: Dependency installation failed, you need install dependencies mannually, then execute $0." && exit 1

# check remote state and write config.
PORTS_TO_DETECT=${PORTS_TO_DETECT-default}
svrports='51887,43612,44956,38119,39577,60800,34589,57559'
[ "x$PORTS_TO_DETECT" != "xdefault" ] && svrports="$PORTS_TO_DETECT"

logi 'Checking if admin host is up, then generate config.'
[ "`nmap $1 -p$svrports -n -sT -Pn | grep -E -e \"0 hosts up\" -e \"closed|filtered\"`x" != "x" ] &&
loge "Remote host $1 not accessable, check remote swoole-admin state." >&2 && exit 11 # 11 for EAGAIN
# save config json
echo "{\"ip\":{\"product\":\""$1"\",\"local\":\"127.0.0.1\"}}" > /opt/swoole/public/sdk/config_ip.conf

# deprecated.
# #configure and start supervisor
# #prepare venv
#rm -rf /opt/swoole/pysandbox
#python -m virtualenv -p python2 --no-site-packages /opt/swoole/pysandbox #python2 alias: see PEP 394
#. /opt/swoole/pysandbox/bin/activate
# for old python/pip versions, -m pip cannot be executed...
#pip install --upgrade --force supervisor/meld3-1.0.2-py2.py3-none-any.whl --force supervisor/supervisor-3.3.5-py2-none-any.whl >>/tmp/na_installer.log 2>&1

# #make supervisor configs
#SUPERVISORCTL="`which supervisorctl`"
#SUPERVISORD="`which supervisord`"
#PIDFILE="/var/run/supervisord-node-agent.pid"
#SOCKFILE="/opt/swoole/supervisor/supervisor.sock"
#MAINCONFFILE="/opt/swoole/supervisor/supervisord.conf"
#NODECONFFILE="/opt/swoole/supervisor/conf.d/node-agent.conf"
#SUPERVISORLOG="/var/log/supervisord-node-agent.log"

#cat >/opt/swoole/script/supervisor.sh <<EOF
##!/bin/sh
#SERVICE_NAME=\$2
#ACTION=\$1
#$SUPERVISORCTL -c $MAINCONFFILE \$ACTION \$SERVICE_NAME 2>&1
#EOF
#chmod 755 /opt/swoole/script/supervisor.sh
#mkdir -p /opt/swoole/supervisor/conf.d
#cat > $MAINCONFFILE <<EOF
#[unix_http_server]
#file=$SOCKFILE
#chmod=0700
#[supervisord]
#logfile=$SUPERVISORLOG
#pidfile=$PIDFILE
#childlogdir=/var/log
#[rpcinterface:supervisor]
#supervisor.rpcinterface_factory = supervisor.rpcinterface:make_main_rpcinterface
#[supervisorctl]
#serverurl=unix://$SOCKFILE
#[include]
#files = /opt/swoole/supervisor/conf.d/*.conf
#EOF
#cat > $NODECONFFILE << EOF
#[program:node-agent]
#command = /opt/swoole/script/php/swoole_php /opt/swoole/node-agent/src/node.php
#stdout_logfile = /opt/swoole/logs/node-agent_stdout.log
#stderr_logfile = /opt/swoole/logs/node-agent_stderr.log
#user = root
#autostart = 1
#autorestart = 1
#startsecs = 2
#stopasgroup = 1
#killasgroup = 1
#stdout_logfile_maxbytes = 500MB
#stdout_logfile_backups = 10
#stderr_logfile_maxbytes = 500MB
#stderr_logfile_backups = 10
#EOF

# add startup scripts for openrc / sysvinit / systemd
if [ "x`rc-status -V 2>&1 | grep -i openrc`" != "x" ]
then
    # openrc init
    logi 'Installing node-agent startup script for OpenRC.'
    cat > /etc/init.d/node-agent  << EOF
#!`if [ -e '/sbin/openrc-run' ]; then echo /sbin/openrc-run; else echo /sbin/runscript; fi;`

name="Swoole Dashboard NodeAgent"
command="/opt/swoole/script/php/swoole_php"
pidfile="/var/run/node-agent.pid"
command_args="/opt/swoole/node-agent/src/node.php"
command_user="root"
command_background="yes"
start_stop_daemon_args="--make-pidfile --stdout /opt/swoole/logs/node-agent_stdout.log --stderr /opt/swoole/logs/node-agent_stderr.log"

depend() {
    need net
}

stop() {
    [ ! -e \${pidfile} ] && return
    ebegin "Stopping \${name}"
    /opt/swoole/script/php/swoole_php /opt/swoole/script/killtree.php \`cat \${pidfile}\`
    retval=\$?
    [ "0" = \$retval ] && rm \${pidfile}
    eend \$retval
}
EOF
    chmod 755 /etc/init.d/node-agent
    rc-service node-agent stop >>/tmp/na_installer.log 2>&1
    rc-service node-agent start >>/tmp/na_installer.log 2>&1
elif [ "x`/proc/1/exe --version 2>&1 | grep -i systemd`" != "x" ] || type systemctl >>/tmp/na_installer.log 2>&1
then
    logi 'Installing node-agent startup script for systemd.'
    cat > /etc/systemd/system/node-agent.service << EOF
[Unit]
Description=Swoole Dashboard NodeAgent
After=network.target

[Service]
Type=simple
PIDFile=/var/run/node-agent.pid
ExecStart=/opt/swoole/script/php/swoole_php /opt/swoole/node-agent/src/node.php
ExecStop=/opt/swoole/script/php/swoole_php /opt/swoole/script/killtree.php \$MAINPID
Restart=on-failure
RestartSec=60s

[Install]
WantedBy=multi-user.target

EOF
    chmod 664 /etc/systemd/system/node-agent.service
    # this may fail in docker 'cause can't access dbus-daemon, thus execute it here.
    systemctl daemon-reload >>/tmp/na_installer.log 2>&1 
    systemctl stop node-agent.service >>/tmp/na_installer.log 2>&1
    systemctl restart node-agent.service >>/tmp/na_installer.log 2>&1
    if [ x`systemctl show node-agent.service -p ActiveState` = x'ActiveState=active' ]
    then
        logi ' Done restart systemd service.'
    else
        logw ' (Re)start systemd service failed (maybe in docker?).'
    fi
elif [ "x`/proc/1/exe --version 2>&1 | grep -i upstart`" != "x" ] ||  type chkconfig >>/tmp/na_installer.log 2>&1
then
    # upstart / sysvlike init
    logi 'Installing node-agent startup script for sysvinit-like systems.'
    cat > /etc/init.d/node-agent  << EOF
#!/bin/bash
#
# node-agent    Swoole Dashboard NodeAgent
#
# chkconfig: 345 99 04
# description: Swoole Dashboard NodeAgent
#
# processname: swoole_php
# pidfile: /var/run/node-agent.pid
#
### BEGIN INIT INFO
# Provides: node-agent
# Required-Start: \$all
# Required-Stop: \$all
# Short-Description: node-agent
# Description:       Swoole Dashboard NodeAgent
### END INIT INFO

# Source function library
. /etc/rc.d/init.d/functions 2>&- ||
. /etc/init.d/functions 2>&- ||
. /lib/lsb/init-functions 2>&1

# Path to the supervisorctl script, server binary,
# and short-form for messages.
prog=node-agent
pidfile="/var/run/node-agent.pid"
lockfile="/var/lock/subsys/node-agent"
STOP_TIMEOUT=60
RETVAL=0

start() {
    echo -n "Starting \$prog... "
    [ -e \${lockfile} ] && echo already started && exit 1
    if [ "\`which start-stop-daemon 2>&- \`x" != "x" ]
    then
        start-stop-daemon --pidfile \${pidfile} --start --startas /bin/bash -- -c '/opt/swoole/script/php/swoole_php /opt/swoole/node-agent/src/node.php >>/opt/swoole/logs/node-agent_stdout.log 2>>/opt/swoole/logs/node-agent_stderr.log & echo -n \$! > '\${pidfile}
        RETVAL=\$?
    else
        daemon --pidfile \${pidfile} '/opt/swoole/script/php/swoole_php /opt/swoole/node-agent/src/node.php >>/opt/swoole/logs/node-agent_stdout.log 2>>/opt/swoole/logs/node-agent_stderr.log & echo -n \$! > '\${pidfile};
        RETVAL=\$?
    fi
    echo
    [ -d /var/lock/subsys ] && touch \${lockfile}
    return \$RETVAL
}

stop() {
    echo -n "Stopping \$prog... "
    [ -e \${pidfile} ] && /opt/swoole/script/php/swoole_php /opt/swoole/script/killtree.php \`cat \${pidfile}\`
    RETVAL=\$?
    echo
    [ \$RETVAL -eq 0 ] && rm -rf \${lockfile} \${pidfile}
}

restart() {
    stop
    start
}

case "\$1" in
    start)
        start
        ;;
    stop)
        stop
        ;;
    status)
        status -p \${pidfile} /opt/swoole/script/php/swoole_php
        ;;
    restart)
        restart
        ;;
    condrestart|try-restart)
        if status -p \${pidfile} /opt/swoole/script/php/swoole_php >&-; then
          restart
        fi
        ;;
    *)
        echo \$"Usage: \$prog {start|stop|restart|condrestart|try-restart}"
        RETVAL=2
esac

exit \$RETVAL
EOF
    chmod 755 /etc/init.d/node-agent
    /etc/init.d/node-agent stop >>/tmp/na_installer.log 2>&1
    /etc/init.d/node-agent restart
else
    logw 'Unable to determine init system type (maybe in docker?).'
    logw 'You can mannually add nodeagent into your init system (or docker entrypoint).'
fi
logi "Note: if you are using node-agent in docker,"
logi "\tmannually add  \`/opt/swoole/script/php/swoole_php /opt/swoole/node-agent/src/node.php\` into your entrypoint."
logi "Note: this script won't enable init script automatically,"
logi "\tuse \`systemctl enable node-agent\`(on systemd systems)"
logi "\tor \`rc-update add node-agent\`(on openrc systems) to enable it."

# #start node agent
#for i in 1 2 3 4 5
#do
#    sleep 1 # wait for supervisord up
#    [ ! -S ${SOCKFILE} ] && continue
#    ${SUPERVISORCTL} -c ${MAINCONFFILE} update
#    [ "`${SUPERVISORCTL} -c ${MAINCONFFILE} status node-agent | grep RUNNING`x" = "x" ] &&
#    ${SUPERVISORCTL} -c ${MAINCONFFILE} start node-agent
#    echo 'Done'
#    exit 0
#done
#
#printf "Exiting... supervisord status unknown, may still starting.\nuse \`${SUPERVISORCTL} -c ${MAINCONFFILE} update\` to update config,\nuse \`${SUPERVISORCTL} -c ${MAINCONFFILE} restart node-agent\` to (re)start\n"

logi Done

