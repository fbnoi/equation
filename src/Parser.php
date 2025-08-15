<?php

namespace Lang\Equation;

use Lang\Equation\Exception\UnexpectedToken;
use Lang\Equation\Expr\Args;
use Lang\Equation\Expr\Binary;
use Lang\Equation\Expr\Bracket;
use Lang\Equation\Expr\Call;
use Lang\Equation\Expr;
use Lang\Equation\Expr\Number;
use Lang\Equation\Expr\Param;

class Parser
{

    // EBNF：
    // Expression  → Term (( '+' | '-' ) Term)*
    // Term        → Factor (( '*' | '/' ) Factor)*
    // Factor      → NUMBER | '(' Expression ')' | IDENTIFIER '(' Arguments? ')'
    // Arguments   → Expression (',' Expression)*

    private TokenStream $tokens;

    
    public function parse(TokenStream $tokens): Expr
    {
        $this->tokens = $tokens;
        $this->tokens->rewind();
        $expr = $this->parseExpression();
        if ($this->tokens->hasNext()) {
            throw new UnexpectedToken($this->tokens->current()->getValue());
        }
        return $expr;
    }

    private function parseExpression(): Expr
    {
        $left = $this->parseTerm();
        while ($token = $this->tokens->current()) {
            if ($token->getType() === Token::OP && ($token->getValue() === '+' || $token->getValue() === '-')) {
                $this->tokens->next();
                $right = $this->parseTerm();
                $left = new Binary($left, $right, $token->getValue());
            } else {
                break;
            }
        }
        return $left;
    }

    private function parseTerm(): Expr
    {
        $left = $this->parseFactor();
        while ($token = $this->tokens->current()) {
            if ($token->getType() === Token::OP && ($token->getValue() === '*' || $token->getValue() === '/')) {
                $this->tokens->next();
                $right = $this->parseFactor();
                $left = new Binary($left, $right, $token->getValue());
            } else {
                break;
            }
        }
        return $left;
    }

    private function parseFactor(): Expr
    {
        $token = $this->tokens->current();
        if (!$token) {
            throw new UnexpectedToken('End of input');
        }

        switch ($token->getType()) {
            case Token::NUMBER:
                $this->tokens->next();
                return new Number($token);
            case Token::PARAM:
                $this->tokens->next();
                return new Param($token);
            case Token::IDENTIFIER:
                return $this->parseFunctionCall();
            case Token::BRACKET:
                if ($token->getValue() === '(') {
                    $this->tokens->next();
                    $expr = $this->parseExpression();
                    if ($this->tokens->current()->getValue() !== ')') {
                        throw new UnexpectedToken('Expected closing bracket');
                    }
                    $this->tokens->next();
                    return new Bracket($expr);
                }
                break;
        }

        throw new UnexpectedToken($token->getValue());
    }

    private function parseFunctionCall(): Expr
    {
        $token = $this->tokens->current();
        if (!$token || $token->getType() !== Token::IDENTIFIER) {
            throw new UnexpectedToken('Expected function identifier');
        }

        $fnName = $token->getValue();
        $this->tokens->next();

        if ($this->tokens->current()->getValue() !== '(') {
            throw new UnexpectedToken('Expected opening bracket for function arguments');
        }
        $this->tokens->next();

        $args = [];
        while ($this->tokens->current() && $this->tokens->current()->getValue() !== ')') {
            $args[] = $this->parseExpression();
            if ($this->tokens->current() && $this->tokens->current()->getValue() === ',') {
                $this->tokens->next();
            }
        }

        if (!$this->tokens->current() || $this->tokens->current()->getValue() !== ')') {
            throw new UnexpectedToken('Expected closing bracket for function arguments');
        }
        $this->tokens->next();

        return new Call($fnName, new Args($args));
    }
}
