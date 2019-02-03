package io.tricking.challenge;

import io.tricking.challenge.spel.SmallEvaluationContext;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.expression.Expression;
import org.springframework.expression.ExpressionParser;
import org.springframework.expression.ParserContext;
import org.springframework.expression.common.TemplateParserContext;
import org.springframework.expression.spel.standard.SpelExpressionParser;
import org.springframework.http.HttpStatus;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;
import org.springframework.ui.Model;
import org.springframework.web.client.HttpClientErrorException;

import javax.servlet.http.Cookie;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

@Controller
public class MainController {
    ExpressionParser parser = new SpelExpressionParser();

    @Autowired
    private KeyworkProperties keyworkProperties;

    @Autowired
    private UserConfig userConfig;

    @GetMapping
    public String admin(@CookieValue(value = "remember-me", required = false) String rememberMeValue,
                        HttpSession session,
                        Model model) {
        if (rememberMeValue != null && !rememberMeValue.equals("")) {
            String username = userConfig.decryptRememberMe(rememberMeValue);
            if (username != null) {
                session.setAttribute("username", username);
            }
        }

        Object username = session.getAttribute("username");
        if(username == null || username.toString().equals("")) {
            return "redirect:/login";
        }

        model.addAttribute("name", getAdvanceValue(username.toString()));
        return "hello";
    }

    @GetMapping("/login")
    public String login() {
        return "login";
    }

    @GetMapping("/login-error")
    public String loginError(Model model) {
        model.addAttribute("loginError", true);
        model.addAttribute("errorMsg", "登陆失败，用户名或者密码错误！");
        return "login";
    }

    @PostMapping("/login")
    public String login(@RequestParam(value = "username", required = true) String username,
                        @RequestParam(value = "password", required = true) String password,
                        @RequestParam(value = "remember-me", required = false) String isRemember,
                        HttpSession session, HttpServletResponse response)
    {
        if (userConfig.getUsername().contentEquals(username) && userConfig.getPassword().contentEquals(password)) {
            session.setAttribute("username", username);

            if (isRemember != null && !isRemember.equals("")) {
                Cookie c = new Cookie("remember-me", userConfig.encryptRememberMe());
                c.setMaxAge(60 * 60 * 24 * 30);
                response.addCookie(c);
            }

            return "redirect:/";
        }
        return "redirect:/login-error";
    }

    @ExceptionHandler(HttpClientErrorException.class)
    @ResponseStatus(HttpStatus.FORBIDDEN)
    public String handleForbiddenException()
    {
        return "forbidden";
    }

    private String getAdvanceValue(String val) {
        for (String keyword: keyworkProperties.getBlacklist()) {
            Matcher matcher = Pattern.compile(keyword, Pattern.DOTALL | Pattern.CASE_INSENSITIVE).matcher(val);
            if (matcher.find()) {
                throw new HttpClientErrorException(HttpStatus.FORBIDDEN);
            }
        }

        ParserContext parserContext = new TemplateParserContext();
        Expression exp = parser.parseExpression(val, parserContext);
        SmallEvaluationContext evaluationContext = new SmallEvaluationContext();
        return exp.getValue(evaluationContext).toString();
    }
}
