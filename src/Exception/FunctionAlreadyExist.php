<?php

namespace Lang\Equation\Exception;

use Exception;

class FunctionAlreadyExist extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Function $name already exist.");
    }
}