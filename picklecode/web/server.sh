#!/usr/bin/env bash

set -ex

BASE_DIR=$(pwd)

exec gunicorn --chdir=${BASE_DIR} -w 2 -k gevent -b 0.0.0.0:8000 -u www-data -g www-data core.wsgi:application
