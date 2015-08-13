<?php

namespace Novuso\Common\Adapter\Presentation\Symfony\Http\Resolver;

/**
 * ResponderMemoryResolver resolves responders from a ResponderMemoryMap
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ResponderMemoryResolver implements ResponderResolver
{
    /**
     * Responder map
     *
     * @var ResponderMemoryMap
     */
    protected $responderMap;

    /**
     * Constructs ResponderMemoryResolver
     *
     * @param ResponderMemoryMap $responderMap The responder map
     */
    public function __construct(ResponderMemoryMap $responderMap)
    {
        $this->responderMap = $responderMap;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($actionClass)
    {
        return $this->responderMap->getResponder($actionClass);
    }
}
