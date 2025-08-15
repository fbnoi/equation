<?php
  
namespace Tests;

use Lang\Equation\Exception\UnexpectedExpression;
use Lang\Equation\Lexer;
use Lang\Equation\Token;
use PHPUnit\Framework\TestCase;
use Throwable;

class LexerTest extends TestCase
{
    /**
     * @throws UnexpectedExpression
     */
    public function testParseInt(): void
    {
        $tokens = Lexer::tokenize(" 1.23");
        $this->assertCount(1, $tokens, 'count');
        $this->assertEquals("1.23", $tokens[0]->getValue(), 'value: ' . $tokens[0]->getValue());
        $this->assertEquals(Token::NUMBER, $tokens[0]->getType(), 'type');
    }

    /**
     * @throws UnexpectedExpression
     */
    public function testParseName(): void
    {
        $tokens = Lexer::tokenize(" :var: ");
        $this->assertCount(1, $tokens);
        $this->assertEquals(":var:", $tokens[0]->getValue());
        $this->assertEquals(Token::PARAM, $tokens[0]->getType());
    }

    /**
     * @throws UnexpectedExpression
     */
    public function testParseOP(): void
    {
        $tokens = Lexer::tokenize("+ -* /^,");
        $this->assertCount(6, $tokens);
        foreach (str_split("+-*/^,") as $k => $v) {
            $this->assertEquals($v, $tokens[$k]->getValue());
            $this->assertEquals(Token::OP, $tokens[$k]->getType());
        }
    }

    public function testParseCall(): void
    {
        $tokens = Lexer::tokenize("add(1)");
        $this->assertCount(4, $tokens);
        $this->assertEquals('add', $tokens[0]->getValue());
        $this->assertEquals(Token::IDENTIFIER, $tokens[0]->getType());
        $this->assertEquals('(', $tokens[1]->getValue());
        $this->assertEquals(Token::BRACKET, $tokens[1]->getType());
        $this->assertEquals('1', $tokens[2]->getValue());
        $this->assertEquals(Token::NUMBER, $tokens[2]->getType());
        $this->assertEquals(')', $tokens[3]->getValue());
        $this->assertEquals(Token::BRACKET, $tokens[3]->getType());
    }
}
