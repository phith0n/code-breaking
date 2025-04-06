package com.ar3h.postgresqljdbcattack;

import com.ar3h.postgresqljdbcattack.security.ForbiddenNetworkAccessSecurityManager;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication
public class PostgresqlJdbcAttackApplication {

    public static void main(String[] args) {
        SpringApplication.run(PostgresqlJdbcAttackApplication.class, args);
    }

}
