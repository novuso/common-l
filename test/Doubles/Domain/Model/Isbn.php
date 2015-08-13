<?php

namespace Novuso\Test\Common\Doubles\Domain\Model;

use Novuso\Common\Domain\Model\StringId;
use Novuso\System\Exception\DomainException;

final class Isbn extends StringId
{
    protected function guardId($id)
    {
        if (!preg_match('/\A[0-9]{3}-[0-9]{10}\z/', $id)) {
            $message = sprintf('Invalid ISBN: %s', $id);
            throw DomainException::create($message);
        }
    }
}
