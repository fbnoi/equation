<?php

namespace Lang\Equation\Expr;

use Lang\Equation\Expr;

class Bracket implements Expr
{
    use ExprTrait;
    
    private Expr $expr;

    public function __construct(Expr $expr)
    {
        $this->expr = $expr;
    }

    /**
     * @param array<string, int|float>|null $params
     */
    public function getValue(?array $params = null): float
    {
        return $this->expr->getValue($params);
    }

    public function raw(): string
    {
        return "(" . $this->expr->raw() . ")";
    }
}
