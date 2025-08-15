<?php

namespace Lang\Equation;

use ArrayAccess;
use Countable;
use Iterator;

class TokenStream implements Countable, ArrayAccess
{
    private int $pos = 0;

    /**
     * @var array<Token>
     */
    private array $tokens;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->tokens[$offset]);
    }

    public function offsetGet($offset): ?Token
    {
        return $this->tokens[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if ($value instanceof Token) {
            if ($offset === null) {
                $this->tokens[] = $value;
            } else {
                $this->tokens[$offset] = $value;
            }
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->tokens[$offset]);
    }

    public function current(): ?Token
    {
        return $this->tokens[$this->pos] ?? null;
    }

    public function next(): ?Token
    {
        $this->pos++;
        return $this->current();
    }

    public function rewind(): void
    {
        $this->pos = 0;
    }

    public function hasNext(): bool
    {
        return isset($this->tokens[$this->pos + 1]);
    }

    public function count(): int
    {
        return count($this->tokens);
    }
}
