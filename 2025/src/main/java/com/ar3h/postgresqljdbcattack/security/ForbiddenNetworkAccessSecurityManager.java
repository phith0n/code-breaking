package com.ar3h.postgresqljdbcattack.security;

import java.security.Permission;

public class ForbiddenNetworkAccessSecurityManager extends SecurityManager {
    @Override
    public void checkPermission(Permission perm) {

    }

    @Override
    public void checkConnect(String host, int port) {
        System.out.println("checkConnect...");
        throw new SecurityException("Network connect operation is forbidden by Security Manager: " + host + ":" + port);
    }
}