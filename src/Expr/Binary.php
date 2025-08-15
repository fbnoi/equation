<?php

namespace Lang\Equation\Expr;

use Lang\Equation\Exception\DividedByZero;
use Lang\Equation\Expr;

class Binary implements Expr
{
    use ExprTrait;

    private Expr $x;
    private Expr $y;
    private string $op;

    public function __construct(Expr $x, Expr $y, string $op)
    {
        $this->x = $x;
        $this->y = $y;
        $this->op = $op;
    }

    public function raw(): string
    {
        return $this->x->raw() .
            $this->op .
            $this->y->raw();
    }

    /**
     * @param array<string, int|float>|null $params
     * 
     * @throws InvalidValue
     * @throws DividedByZero
     */
    public function getValue(?array $params = null, int $scale = Expr::DEFAULT_MAX_SCALE): float
    {
        $x = $this->x->getValue($params);
        $y = $this->y->getValue($params);
        if ($this->op == Expr::OP_DIV && $y == 0) {
            throw new DividedByZero($this->raw());
        }
        $ret = (float) match ($this->op) {
            Expr::OP_ADD => bcadd($x, $y, Expr::DEFAULT_MAX_SCALE),
            Expr::OP_SUB => bcsub($x, $y, Expr::DEFAULT_MAX_SCALE),
            Expr::OP_MUL => bcmul($x, $y, Expr::DEFAULT_MAX_SCALE),
            Expr::OP_DIV => bcdiv($x, $y, Expr::DEFAULT_MAX_SCALE),
        };

        return round($ret, $scale);
    }
}
