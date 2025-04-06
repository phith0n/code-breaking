package com.ar3h.postgresqljdbcattack.controller;

import com.ar3h.postgresqljdbcattack.security.ForbiddenNetworkAccessSecurityManager;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;

import java.io.PrintWriter;
import java.io.StringWriter;
import java.sql.DriverManager;

/**
 * @Author Ar3h
 * @Date 2025/4/3 19:31
 */
@Controller
public class IndexController {

    @ResponseBody
    @RequestMapping("/jdbc")
    public String jdbc(String url) {
        try {
            System.setSecurityManager(new ForbiddenNetworkAccessSecurityManager());

            DriverManager.getConnection(url);

        } catch (Exception e) {
            StringWriter sw = new StringWriter();
            e.printStackTrace(new PrintWriter(sw));
            return sw.toString();
        }
        return "done.";
    }

}
