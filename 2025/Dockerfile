FROM maven:3.9-eclipse-temurin-8 AS builder

WORKDIR /app

COPY src /app/src
COPY pom.xml /app/pom.xml

RUN mvn clean package assembly:single -DskipTests

FROM eclipse-temurin:8-jre

COPY --from=builder --chown=www-data:www-data /app/target/PostgresqlJdbcAttack-0.0.1-SNAPSHOT.jar /app/PostgresqlJdbcAttack-0.0.1-SNAPSHOT.jar
COPY --chown=www-data:www-data run.sh /app/run.sh

RUN set -ex \
    && chown -R www-data:www-data /app \
    && chmod +x /app/run.sh

WORKDIR /app
EXPOSE 8080
USER www-data

CMD ["bash", "/app/run.sh"]
