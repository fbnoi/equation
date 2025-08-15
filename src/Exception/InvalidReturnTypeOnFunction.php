<?php

namespace Lang\Equation\Exception;

use Exception;

class InvalidReturnTypeOnFunction extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Function $name must return int or float type");
    }
}