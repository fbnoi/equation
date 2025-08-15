<?php

namespace Lang\Equation\Exception;

use Exception;

class FunctionNotExist extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Function $name not exist.");
    }
}