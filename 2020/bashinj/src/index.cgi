#!/bin/bash

source ./_dep/web.cgi
echo_headers

name=${_GET["name"]}

[[ $name == "" ]] && name='Bob'

curl -v http://httpbin.org/get?name=$name
