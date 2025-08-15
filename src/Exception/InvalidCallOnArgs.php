<?php

namespace Lang\Equation\Exception;

use Exception;

class InvalidCallOnArgs extends Exception
{
    public function __construct()
    {
        parent::__construct("Invalid call on arguments expr");
    }
}