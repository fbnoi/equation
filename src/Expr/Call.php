<?php

namespace Lang\Equation\Expr;

use Lang\Equation\Exception\FunctionNotExist;
use Lang\Equation\Exception\CallFunctionFailed;
use Lang\Equation\FunctionMap;
use Lang\Equation\Expr;

class Call implements Expr
{
    use ExprTrait;

    private string $fn;

    private Args $args;

    public function __construct(string $fn, Args $args)
    {
        $this->fn = $fn;
        $this->args = $args;
    }

    public function getValue(?array $params = null): float
    {
        $args = $this->args->getArrayListValue($params);
        if ($fn = FunctionMap::get($this->fn)) {
            $ret = call_user_func_array($fn, $args);
            if (is_numeric($ret)) {
                return (float) $ret;
            }
            throw new CallFunctionFailed($this->fn, $args);
        }
        throw new FunctionNotExist($this->fn);
    }

    public function raw(): string
    {
        return $this->fn .
            '(' .
            $this->args->raw() .
            ')';
    }
}
