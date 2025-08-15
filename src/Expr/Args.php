<?php

namespace Lang\Equation\Expr;

use Lang\Equation\Exception\InvalidCallOnArgs;
use Lang\Equation\Expr;

class Args implements Expr
{
    /**
     * @var Expr[] $args
     */
    private array $args;

    /**
     * @param Expr[] $args
     */
    public function __construct(array $args = [])
    {
        $this->args = $args;
    }

    /**
     * @param array<string, mixed> $params
     * 
     * @return array<float>
     */
    public function getArrayListValue(?array $params = null): array
    {
        return array_map(fn(Expr $expr) => $expr->getValue($params), $this->args);
    }

    public function raw(): string
    {
        return join(',', array_map(fn(Expr $arg) => $arg->raw(), $this->args));
    }

    public function getValue(?array $params = null): float
    {
        throw new InvalidCallOnArgs();
    }

    public function add(Expr $expr): Expr
    {
        throw new InvalidCallOnArgs();
    }

    public function sub(Expr $expr): Expr
    {
        throw new InvalidCallOnArgs();
    }

    public function mul(Expr $expr): Expr
    {
        throw new InvalidCallOnArgs();
    }

    public function div(Expr $expr): Expr
    {
        throw new InvalidCallOnArgs();
    }
}
