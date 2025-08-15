<?php

namespace Tests;

use Lang\Equation\Exception\DividedByZero;
use Lang\Equation\Expr\Binary;
use Lang\Equation\Expr;
use Lang\Equation\FunctionMap;
use Lang\Equation\Lexer;
use Lang\Equation\Parser;
use PHPUnit\Framework\TestCase;

class ExprTest extends TestCase
{
    public function parseExpr(string $expr): Expr
    {
        $tokens = Lexer::tokenize($expr);
        $parser = new Parser();
        return $parser->parse($tokens);
    }

    public function testNumber()
    {
        $expr = $this->parseExpr("1");
        $this->assertEquals(1, $expr->getValue());
    }

    public function testParam()
    {
        $expr = $this->parseExpr(":var:");
        $this->assertEquals(1, $expr->getValue(['var' => 1]));
    }

    public function testBinary()
    {
        $expr = $this->parseExpr("1+1");
        $this->assertEquals(2, $expr->getValue());
    }

    public function testBracket()
    {
        $expr = $this->parseExpr("(1)");
        $this->assertEquals(1, $expr->getValue());
    }

    public function testFunctionCall()
    {
        FunctionMap::set('max', fn(float $a, float $b) => max($a, $b));
        $expr = $this->parseExpr("max(1,2)");
        $this->assertEquals(2, $expr->getValue());
    }

    public function testOperation()
    {
        $expr1 = $this->parseExpr("1");
        $expr2 = $this->parseExpr("1");
        $expr3 = $this->parseExpr("0");
        $add = $expr1->add($expr2);
        $sub = $expr1->sub($expr2);
        $mul = $expr1->mul($expr2);
        $div = $expr1->div($expr2);
        $div0 = $expr1->div($expr3);
        $this->assertInstanceOf(Binary::class, $add);
        $this->assertInstanceOf(Binary::class, $sub);
        $this->assertInstanceOf(Binary::class, $mul);
        $this->assertInstanceOf(Binary::class, $div);
        $this->assertEquals(2, $add->getValue());
        $this->assertEquals(0, $sub->getValue());
        $this->assertEquals(1, $mul->getValue());
        $this->assertEquals(1, $div->getValue());
        try {
            $div0->getValue();
        } catch (\Throwable $th) {
            $this->assertInstanceOf(DividedByZero::class, $th);
        }
    }
}
