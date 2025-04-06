#!/bin/bash

if [ "$1" = "enable" ]; then
    iptables -F DOCKER-USER
    iptables -I DOCKER-USER 1 -m conntrack --ctstate RELATED,ESTABLISHED -j ACCEPT
    iptables -I DOCKER-USER 2 -s 172.18.0.0/16 -p udp --dport 53 -j ACCEPT
    iptables -I DOCKER-USER 3 -s 172.18.0.0/16 -p tcp --dport 53 -j ACCEPT
    iptables -A DOCKER-USER -s 172.18.0.0/16 -j REJECT
elif [ "$1" = "disable" ]; then
    iptables -F DOCKER-USER
else
    echo "Usage: $0 enable|disable"
fi