<?php

namespace Novuso\Test\Common\Doubles\Domain\Model;

use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;

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

    public static function fromString($name)
    {
        $parts = explode(' ', trim($name));

        if (count($parts) < 2) {
            $message = sprintf('%s expects at least first and last name', __METHOD__);
            throw DomainException::create($message);
        }

        if (count($parts) === 2) {
            return new self($parts[0], $parts[1]);
        }

        return new self($parts[0], $parts[1], $parts[2]);
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
