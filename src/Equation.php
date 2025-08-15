<?php

namespace Lang\Equation;

use Lang\Equation\Exception\InvalidValue;
use Lang\Equation\Exception\ParseExpressionFailed;
use Lang\Equation\Exception\UnexpectedExpression;
use Lang\Equation\Exception\UnexpectedToken;
use Lang\Equation\Expr;

class Equation
{
    /**
     * @throws ParseExpressionFailed
     */
    public function parse(string $expr): Expr
    {
        try {
            $tokens = Lexer::tokenize($expr);
            $parser = new Parser();

            return $parser->parse($tokens);
        } catch (UnexpectedExpression|UnexpectedToken $e) {
            throw new ParseExpressionFailed("parse expression $expr failed at " . $e->getMessage(), 0, $e);
        } catch (InvalidValue $e) {
            throw new ParseExpressionFailed("parse expression $expr failed as " . $e->getMessage(), 0, $e);
        }
    }
}
