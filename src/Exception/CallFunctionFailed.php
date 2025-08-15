<?php

namespace Lang\Equation\Exception;

use Exception;

class CallFunctionFailed extends Exception
{
    public function __construct(string $functionName, array $params)
    {
        $message = "Failed to call function: {$functionName} with parameters: " . join(', ', array_map(fn($p) => var_export($p, true), $params));
        parent::__construct($message);
    }
}