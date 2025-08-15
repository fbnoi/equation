<?php

namespace Tests;

use Lang\Equation\Exception\InvalidValue;
use Lang\Equation\Exception\NoValueIsProvided;
use Lang\Equation\Exception\UnexpectedExpression;
use Lang\Equation\Exception\UnexpectedToken;
use Lang\Equation\Lexer;
use Lang\Equation\Parser;
use Lang\Equation\Expr\Binary;
use Lang\Equation\Expr\Bracket;
use Lang\Equation\Expr;
use Lang\Equation\Expr\Number;
use Lang\Equation\Expr\Param;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{

    public function parseExpr(string $expr): Expr
    {
        $tokens = Lexer::tokenize($expr);
        $parser = new Parser();
        return $parser->parse($tokens);
    }

    /**
     * @throws UnexpectedToken
     * @throws InvalidValue
     * @throws UnexpectedExpression
     */
    public function testParseNum(): void
    {
        $expr = $this->parseExpr(" 1.23");
        $this->assertInstanceOf(Number::class, $expr);
        $this->assertEquals('1.23', $expr->raw());
    }

    /**
     * @throws UnexpectedToken
     * @throws InvalidValue
     * @throws UnexpectedExpression
     * @throws NoValueIsProvided
     */
    public function testParseParam(): void
    {
        $expr = $this->parseExpr(" :var:");
        $this->assertInstanceOf(Param::class, $expr);
        $this->assertEquals(':var:', $expr->raw());
    }

    /**
     * @throws UnexpectedToken
     * @throws InvalidValue
     * @throws UnexpectedExpression
     */
    public function testParseBracket(): void
    {
        $expr = $this->parseExpr("( 1) ");
        $this->assertInstanceOf(Bracket::class, $expr);
        $this->assertEquals('(1)', $expr->raw());
    }

    /**
     * @throws UnexpectedToken
     * @throws InvalidValue
     * @throws UnexpectedExpression
     */
    public function testParseBinary(): void
    {
        $expr = $this->parseExpr("1 + 1");
        $this->assertInstanceOf(Binary::class, $expr);
        $this->assertEquals('1+1', $expr->raw());
    }

    /**
     * @throws UnexpectedToken
     * @throws InvalidValue
     * @throws UnexpectedExpression
     */
    public function testParseBinary2(): void
    {
        $expr = $this->parseExpr("1 + :var:");
        $this->assertInstanceOf(Binary::class, $expr);
        $this->assertEquals('1+:var:', $expr->raw());
    }


    public function testFuncCallParse(): void
    {
        $expr = $this->parseExpr("1+func(1,2,3)");
        $this->assertInstanceOf(Expr::class, $expr);
        $this->assertEquals("1+func(1,2,3)", $expr->raw());
    }

    /**
     * @throws UnexpectedToken
     * @throws InvalidValue
     * @throws UnexpectedExpression
     */
    public function testParseComplex(): void
    {
        $tokens = Lexer::tokenize("3.14+2*4*(1+1.1)+:var:+:test:");
        $parser = new Parser();
        $expr = $parser->parse($tokens);
        $this->assertInstanceOf(Binary::class, $expr);
        $this->assertEquals(23.94, $expr->getValue(['var' => 2, 'test' => 2]), $expr->raw());
        $this->assertEquals(25.04, $expr->getValue(['var' => 1.1, 'test' => 4]));
        $this->assertEquals('3.14+2*4*(1+1.1)+:var:+:test:', $expr->raw());
    }
}
