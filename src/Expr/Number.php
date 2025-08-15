<?php

namespace Lang\Equation\Expr;

use Lang\Equation\Exception\InvalidToken;
use Lang\Equation\Exception\InvalidValue;
use Lang\Equation\Token;
use Lang\Equation\Expr;

class Number implements Expr
{
    use ExprTrait;

    private float $value;

    /**
     * @throws InvalidToken
     */
    public function __construct(Token $token)
    {
        if (Token::NUMBER !== $token->getType()) {
            throw new InvalidToken(Token::NUMBER, $token->getType());
        }
        $this->value = (float) $token->getValue();
    }

    /**
     * @param array<string, int|float>|null $params
     */
    public function getValue(?array $params = null): float
    {
        return $this->value;
    }

    public function raw(): string
    {
        return (string)$this->value;
    }
}
