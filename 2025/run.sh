#!/bin/bash

if [ -z "$JAVA_OPTS" ]; then
    JAVA_OPTS="-Xms512m -Xmx2g -XX:+UseG1GC"
fi

java ${JAVA_OPTS:-} -jar /app/PostgresqlJdbcAttack-0.0.1-SNAPSHOT.jar
