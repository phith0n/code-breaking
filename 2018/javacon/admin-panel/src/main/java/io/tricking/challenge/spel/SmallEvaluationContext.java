package io.tricking.challenge.spel;

import org.springframework.expression.ConstructorResolver;
import org.springframework.expression.spel.support.StandardEvaluationContext;

import java.util.Collections;
import java.util.List;

public class SmallEvaluationContext extends StandardEvaluationContext {
    public void setConstructorResolvers(List<ConstructorResolver> constructorResolvers) { }
    public List<ConstructorResolver> getConstructorResolvers() {
        return Collections.emptyList();
    }
}
