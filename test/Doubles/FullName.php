<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Model\ValueObject;

final class FullName extends ValueObject
{
    protected $first;
    protected $last;
    protected $middle;

    private function __construct($first, $last, $middle = null)
    {
        $this->first = $first;
        $this->last = $last;
        $this->middle = $middle;
    }

    public static function fromParts($first, $last, $middle = null)
    {
        return new self($first, $last, $middle);
    }

    public function first()
    {
        return $this->first;
    }

    public function last()
    {
        return $this->last;
    }

    public function middle()
    {
        return $this->middle;
    }

    public function toString()
    {
        if ($this->middle !== null) {
            return sprintf('%s %s %s', $this->first, $this->middle, $this->last);
        }

        return sprintf('%s %s', $this->first, $this->last);
    }
}
