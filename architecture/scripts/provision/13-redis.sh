#!/bin/bash

echo " > Redis - Install"

apt-get -qq -y install redis-server > /dev/null

echo " > Redis - Configure"

rm -f                    /etc/redis/redis-cache.conf
cp /etc/redis/redis.conf /etc/redis/redis-cache.conf
chown redis:redis        /etc/redis/redis-cache.conf
sed -i -e 's@^dbfilename .*@dbfilename dump-cache.rdb@'                                        /etc/redis/redis-cache.conf
sed -i -e 's@^port .*@port 6379@'                                                              /etc/redis/redis-cache.conf
sed -i -e 's@^databases .*@databases 2@'                                                       /etc/redis/redis-cache.conf
sed -i -e 's@^logfile .*@logfile /var/log/redis/redis-server-cache.log@'                       /etc/redis/redis-cache.conf
sed -i -e 's@^pidfile .*@pidfile /var/run/redis-cache/redis-server.pid@'                       /etc/redis/redis-cache.conf

rm -f                    /etc/redis/redis-session.conf
cp /etc/redis/redis.conf /etc/redis/redis-session.conf
chown redis:redis        /etc/redis/redis-session.conf
sed -i -e 's@^dbfilename .*@dbfilename dump-session.rdb@'                                      /etc/redis/redis-session.conf
sed -i -e 's@^port .*@port 6380@'                                                              /etc/redis/redis-session.conf
sed -i -e 's@^databases .*@databases 1@'                                                       /etc/redis/redis-session.conf
sed -i -e 's@^logfile .*@logfile /var/log/redis/redis-server-session.log@'                     /etc/redis/redis-session.conf
sed -i -e 's@^pidfile .*@pidfile /var/run/redis-session/redis-server.pid@'                     /etc/redis/redis-session.conf

echo " > Redis - Service"

if [[ "$ENV_TYPE" = "docker" ]]; then
    echo "@todo"
else
    systemctl disable -q redis-server
    systemctl stop    -q redis-server

    systemctl enable -q redis-server@cache
    systemctl start  -q redis-server@cache

    systemctl enable -q redis-server@session
    systemctl start  -q redis-server@session
fi

echo " > Redis - Instance Cache -  Ping"

redis-cli -h 127.0.0.1 -p 6379 ping

echo " > Redis - Instance Session -  Ping"

redis-cli -h 127.0.0.1 -p 6380 ping
