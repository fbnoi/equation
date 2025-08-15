<?php

namespace Lang\Equation\Exception;

use Exception;

class InvalidFunctionName extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Function name $name invalid");
    }
}