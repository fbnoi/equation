<?php

namespace Lang\Equation\Exception;

use Exception;
use Lang\Equation\Token;

class InvalidToken extends Exception
{
    public function __construct(int $expected, int $got)
    {
        $expectedType = Token::TYPES[$expected] ?? 'unknown';
        $gotType = Token::TYPES[$got] ?? 'unknown';

        $message = "Expected token of type '$expectedType', got '$gotType'";
        parent::__construct($message);
    }
}