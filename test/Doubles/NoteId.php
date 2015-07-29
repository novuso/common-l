<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Identifier\StringId;
use Novuso\System\Exception\DomainException;

final class NoteId extends StringId
{
    protected function guardId($id)
    {
        if (!preg_match('/\A[a-z]{1,20}\z/', $id)) {
            $message = 'Note name must be between 1-20 lowercase letters';
            throw DomainException::create($message);
        }
    }
}
