<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Model\ValueObject;
use Novuso\System\Exception\DomainException;

final class Email extends ValueObject
{
    protected $email;

    private function __construct($email)
    {
        assert(is_string($email));

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $message = sprintf('Invalid email address: %s', $email);
            throw DomainException::create($message);
        }

        $this->email = $email;
    }

    public static function fromString($email)
    {
        return new self($email);
    }

    public function toString()
    {
        return $this->email;
    }
}
