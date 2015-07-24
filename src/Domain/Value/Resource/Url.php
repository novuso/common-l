<?php

namespace Novuso\Common\Domain\Value\Resource;

use Novuso\System\Exception\DomainException;

/**
 * Url is a uniform resource identifier that defines a network location
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class Url extends Uri
{
    /**
     * Default ports
     *
     * @var array
     */
    protected static $defaultPorts = [
        'http'  => 80,
        'https' => 443
    ];

    /**
     * Normalizes the query
     *
     * Sorts query by key and removes values without keys.
     *
     * @param string|null $query The query
     *
     * @return string|null
     *
     * @throws DomainException When the query is invalid
     */
    protected static function normalizeQuery($query)
    {
        if (null === $query) {
            return null;
        }

        if ('' === $query) {
            return '';
        }

        $parts = [];
        $order = [];

        // sort query params by key and remove missing keys
        foreach (explode('&', $query) as $param) {
            if ('' === $param || '=' === $param[0]) {
                continue;
            }
            $parts[] = $param;
            $kvp = explode('=', $param, 2);
            $order[] = $kvp[0];
        }

        array_multisort($order, SORT_ASC, $parts);

        return parent::normalizeQuery(implode('&', $parts));
    }
}
