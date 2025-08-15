<?php

namespace Lang\Equation;

use Lang\Equation\Exception\UnexpectedExpression;

class Lexer
{

    public const NUMBER = '/^\d+(\.\d+)?+/';
    public const BRACKET = '/^[()]/';
    public const OP = '/^[+\-*\/^,]/';
    public const PARAM = '/^:[a-zA-Z]+:/';
    public const IDENTIFIER = '/^[a-zA-Z]([0-9a-zA-Z_]?)+/';

    /**
     * @throws UnexpectedExpression
     */
    public static function tokenize(string $expression): TokenStream
    {
        $tokens = [];
        while (true) {
            $expression = trim($expression);
            if (preg_match(self::NUMBER, $expression, $matches)) {
                $tokens[] = Token::number($matches[0]);
            } elseif (preg_match(self::BRACKET, $expression, $matches)) {
                $tokens[] = Token::bracket($matches[0]);
            } elseif (preg_match(self::OP, $expression, $matches)) {
                $tokens[] = Token::operator($matches[0]);
            } elseif (preg_match(self::PARAM, $expression, $matches)) {
                $tokens[] = Token::param($matches[0]);
            } elseif (preg_match(self::IDENTIFIER, $expression, $matches)) {
                $tokens[] = Token::identifier($matches[0]);
            } else {
                throw new UnexpectedExpression($expression);
            }
            if ($matches) {
                $expression = substr($expression, strlen($matches[0]));
            }
            if (!$expression) {
                break;
            }
        }

        return new TokenStream($tokens);
    }
}
