<?php

namespace Lang\Equation\Expr;

use Lang\Equation\Exception\InvalidToken;
use Lang\Equation\Exception\InvalidValue;
use Lang\Equation\Exception\NoValueIsProvided;
use Lang\Equation\Token;
use Lang\Equation\Expr;

class Param implements Expr
{
    use ExprTrait;
    
    private ?string $name;

    public function __construct(Token $token)
    {
        if (Token::PARAM !== $token->getType()) {
            throw new InvalidToken(Token::PARAM, $token->getType());
        }
        $this->name = trim($token->getValue(), ':');
    }

    /**
     * @param array<string, int|float>|null $params
     *
     * @throws NoValueIsProvided
     * @throws InvalidValue
     */
    public function getValue(?array $params = null): float
    {
        if ($num = $params[$this->name] ?? false) {
            if (is_numeric($num)) {
                return $params[$this->name];
            }

            throw new InvalidValue($num);
        }

        throw new NoValueIsProvided($this->name);
    }

    public function raw(): string
    {
        return ":$this->name:";
    }
}
