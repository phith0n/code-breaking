package com.ar3h.postgresqljdbcattack.config;

import com.ar3h.postgresqljdbcattack.filter.SecurityFilter;
import org.springframework.boot.web.servlet.FilterRegistrationBean;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

@Configuration
public class FilterConfig {

    @Bean
    public FilterRegistrationBean<SecurityFilter> timingFilterRegistration() {
        FilterRegistrationBean<SecurityFilter> registration = new FilterRegistrationBean<>();
        registration.setFilter(new SecurityFilter());
        registration.addUrlPatterns("/*"); // 设置过滤器拦截的URL模式  
        return registration;
    }
}