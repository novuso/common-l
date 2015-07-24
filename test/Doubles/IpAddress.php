<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Value\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Utility\VarPrinter;

final class IpAddress extends ValueObject
{
    protected $ipAddress;

    private function __construct($ipAddress)
    {
        if (!is_string($ipAddress)) {
            $message = sprintf(
                '%s expects $ipAddress to be a string; received (%s) %s',
                __METHOD__,
                gettype($ipAddress),
                VarPrinter::toString($ipAddress)
            );
            throw TypeException::create($message);
        }

        if (filter_var($ipAddress, FILTER_VALIDATE_IP) === false) {
            $message = sprintf(
                'Invalid IP address: %s',
                VarPrinter::toString($ipAddress)
            );
            throw DomainException::create($message);
        }

        $this->ipAddress = $ipAddress;
    }

    public static function fromString($state)
    {
        return new self($state);
    }

    public function toString()
    {
        return $this->ipAddress;
    }
}
