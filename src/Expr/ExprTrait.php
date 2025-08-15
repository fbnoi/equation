<?php

namespace Lang\Equation\Expr;

use Lang\Equation\Expr;

trait ExprTrait
{
    function add(Expr $expr): Expr
    {
        return new Binary($this, $expr, Expr::OP_ADD);
    }

    function sub(Expr $expr): Expr
    {
        return new Binary($this, $expr, Expr::OP_SUB);
    }

    function mul(Expr $expr): Expr
    {
        return new Binary($this, $expr, Expr::OP_MUL);
    }

    function div(Expr $expr): Expr
    {
        return new Binary($this, $expr, Expr::OP_DIV);
    }
}
