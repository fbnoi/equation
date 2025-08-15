<?php

namespace Lang\Equation;

class Token {
    public const NUMBER = 1;
    public const PARAM = 2;
    public const OP = 3;
    public const BRACKET = 4;
    public const IDENTIFIER = 5;

    public const TYPES = [
        self::NUMBER => 'number',
        self::PARAM => 'param',
        self::OP => 'operator',
        self::BRACKET => 'bracket',
        self::IDENTIFIER => 'identifier',
    ];

    private int $type;
    private string $value;

    public static function instance(int $type, string $value): static
    {
        $token = new static();
        $token->type = $type;
        $token->value = $value;

        return $token;
    }

    public static function number(string $value): static
    {
        return static::instance(static::NUMBER, $value);
    }

    public static function param(string $name): static
    {
        return static::instance(static::PARAM, $name);
    }

    public static function operator(string $op): static
    {
        return static::instance(static::OP, $op);
    }

    public static function bracket(string $bk): static
    {
        return static::instance(static::BRACKET, $bk);
    }

    public static function identifier(string $identifier): static
    {
        return static::instance(static::IDENTIFIER, $identifier);
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
