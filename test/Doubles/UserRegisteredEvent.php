<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Event\DomainEvent;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\VarPrinter;

final class UserRegisteredEvent implements DomainEvent
{
    protected $fullName;
    protected $username;

    public function __construct($fullName, $username)
    {
        $this->fullName = (string) $fullName;
        $this->username = (string) $username;
    }

    public static function deserialize(array $data)
    {
        $keys = ['fullName', 'username'];
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                $message = sprintf(
                    '%s requires key (%s); received %s',
                    __METHOD__,
                    $key,
                    VarPrinter::toString($data)
                );
                throw DomainException::create($message);
            }
        }

        return new self($data['fullName'], $data['username']);
    }

    public function serialize()
    {
        return [
            'fullName' => $this->fullName,
            'username' => $this->username
        ];
    }

    public function fullName()
    {
        return $this->fullName;
    }

    public function username()
    {
        return $this->username;
    }
}
