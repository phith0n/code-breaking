package com.ar3h.postgresqljdbcattack.filter;

import org.springframework.core.annotation.Order;
import org.springframework.web.filter.OncePerRequestFilter;

import javax.servlet.FilterChain;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.lang.annotation.Annotation;

/**
 * @Author Ar3h
 * @Date 2025/4/3 19:37
 */
public class SecurityFilter extends OncePerRequestFilter implements Order {
    @Override
    protected void doFilterInternal(HttpServletRequest request, HttpServletResponse response, FilterChain filterChain) throws ServletException, IOException {
        String url = request.getParameter("url");
        if (url != null) {
            if (url.toLowerCase().contains("jdbc:postgresql") && url.toLowerCase().contains("socketFactory".toLowerCase())) {
                response.setStatus(HttpServletResponse.SC_FORBIDDEN);
                response.getWriter().write("url is not security");
                return;
            }
        }
        filterChain.doFilter(request, response);
    }

    @Override
    public int value() {
        return 0;
    }

    @Override
    public Class<? extends Annotation> annotationType() {
        return null;
    }
}
