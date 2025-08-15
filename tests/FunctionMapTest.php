<?php

namespace Tests;
use Lang\Equation\Exception\DividedByZero;
use Lang\Equation\Exception\InvalidFunctionName;
use Lang\Equation\Exception\InvalidReturnTypeOnFunction;
use Lang\Equation\Expr\Binary;
use Lang\Equation\Expr;
use Lang\Equation\FunctionMap;
use Lang\Equation\Lexer;
use Lang\Equation\Parser;
use PHPUnit\Framework\TestCase;

class FunctionMapTest extends TestCase
{
    public function testNameValidate()
    {
        $this->expectException(InvalidFunctionName::class);
        FunctionMap::set('0A', fn() => 1);
        FunctionMap::set('A B', fn() => 1);
        FunctionMap::set('!A', fn() => 1);
        $this->expectException(InvalidReturnTypeOnFunction::class);
        FunctionMap::set('A', fn() => "1");
    }
}