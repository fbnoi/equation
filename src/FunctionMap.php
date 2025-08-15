<?php

namespace Lang\Equation;

use Lang\Equation\Exception\FunctionAlreadyExist;
use Lang\Equation\Exception\InvalidReturnTypeOnFunction;
use Lang\Equation\Exception\InvalidFunctionName;
use ReflectionFunction;

class FunctionMap
{
    /**
     * @var array<string, callable>
     */
    private array $map;

    private static FunctionMap $instance;

    private function __construct()
    {
        $this->map = [
            'pi' => static fn() => pi(),
            'e' => static fn() => exp(1),
            'sqrt' => static fn(float $x) => sqrt($x),
            'abs' => static fn(float $x) => abs($x),
            'log' => static fn(float $x, float $base = M_E) => log($x, $base),
            'sin' => static fn(float $x) => sin($x),
            'cos' => static fn(float $x) => cos($x),
            'tan' => static fn(float $x) => tan($x),
            'asin' => static fn(float $x) => asin($x),
            'acos' => static fn(float $x) => acos($x),
            'atan' => static fn(float $x) => atan($x),
        ];
    }

    public static function get(string $name): ?callable
    {
        $instance = self::getInstance();

        return $instance->map[$name] ?? null;
    }

    public static function set(string $name, callable $callable): void
    {
        $instance = self::getInstance();
        if (!$instance->validFnName($name)) {
            throw new InvalidFunctionName($name);
        }
        if (!$instance->validCallable($callable)) {
            throw new InvalidReturnTypeOnFunction($name);
        }
        if (isset($instance->map[$name])) {
            throw new FunctionAlreadyExist($name);
        }
        $instance->map[$name] = $callable;
    }

    private static function getInstance(): FunctionMap
    {
        if (!isset(self::$instance)) {
            self::$instance = new FunctionMap();
        }

        return self::$instance;
    }

    private function validFnName(string $name): bool
    {
        return preg_match("/^[a-zA-Z]([0-9a-zA-Z_]?)+$/", $name);
    }

    private function validCallable(callable $callable): bool
    {
        $reflectionFunction = new ReflectionFunction($callable);
        $returnType = $reflectionFunction->getReturnType();
        if ($returnType) {
            return in_array($returnType, ['int', 'float']);
        }

        return is_null($returnType);
    }
}
