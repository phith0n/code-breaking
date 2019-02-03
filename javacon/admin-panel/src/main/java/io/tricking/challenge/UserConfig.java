package io.tricking.challenge;

import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.stereotype.Component;

@Component
@ConfigurationProperties(prefix = "user")
public class UserConfig {
    private String username;

    private String password;

    private String rememberMeKey;

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }

    public String getRememberMeKey() {
        return rememberMeKey;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public void setRememberMeKey(String rememberMeKey) {
        this.rememberMeKey = rememberMeKey;
    }

    public String encryptRememberMe() {
        String encryptd = Encryptor.encrypt(rememberMeKey, "0123456789abcdef", username);
        return encryptd;
    }

    public String decryptRememberMe(String encryptd) {
        return Encryptor.decrypt(rememberMeKey, "0123456789abcdef", encryptd);
    }
}
