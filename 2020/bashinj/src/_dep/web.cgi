#!/bin/bash
WEBASH_VERSION='0.1'

# Some notes:
# - The aim is to use native bash as much as possible
# - printf is more secure than echo because you can't tell echo to stop processing parameters

# Make bash intolerant of errors
# set -ef -o pipefail

url_decode() {
    local data="${*//+/ }"
    printf '%b' "${data//%/\\x}"
}

url_encode() {
    # Modified from https://gist.github.com/cdown/1163649
    old_lc_collate="$LC_COLLATE"
    LC_COLLATE="C"
    
    local length="${#1}"
    for (( pos = 0; pos < length; pos++ )); do
        local chr="${1:pos:1}"
        case "$chr" in
            ' ')
                printf '+'
                ;;
            [a-zA-Z0-9.~_-])
                printf "%s" "$chr"
                ;;
            *)
                printf '%%%02X' "'$chr"
                ;;
        esac
    done
    
    LC_COLLATE="$old_lc_collate"
}

html() {
    local str="$1"
    str="${str//</&lt;}"
    str="${str//>/&gt;}"
    str="${str//\"/&quot;}"
    printf "%s" "$str"
}

http_status_code="200 OK"
http_status() {
    http_status_code="$1"
}

declare -A http_headers
http_header() {
    http_headers["$1"]="$2"
}

echo_headers() {
    http_header "Content-Type" "text/html;charset=utf-8"
    http_header "X-Server" "webash/$WEBASH_VERSION"
    
    # Output headers
    printf "Status: %s\r\n" "$http_status_code"
    for name in "${!http_headers[@]}"; do 
        printf "%s: %s\r\n" "$name" "${http_headers[$name]}"; 
    done
    
    printf "\r\n"
}

http_error() {
    printf "Status: 500 Internal Server Error\r\n\r\nInternal Server Error"
    exit 1
}

# Parse the query into $_GET
IFS='&;' read -a query <<< "$QUERY_STRING"
declare -A _GET
for name_value_str in "${query[@]}"; do
    IFS='=' read -a name_value <<< "$name_value_str"
    name="$(url_decode "${name_value[0]}")"
    value="$(url_decode "${name_value[1]}")"
    _GET["$name"]="$value"
done

# Parse the POST into $_POST
IFS='&;' read -a query
declare -A _POST
for name_value_str in "${query[@]}"; do
    IFS='=' read -a name_value <<< "$name_value_str"
    name="$(url_decode "${name_value[0]}")"
    value="$(url_decode "${name_value[1]}")"
    _POST["$name"]="$value"
done
