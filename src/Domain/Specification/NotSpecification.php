<?php

namespace Novuso\Common\Domain\Specification;

/**
 * NotSpecification is a logical 'NOT' for a specification
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class NotSpecification extends CompositeSpecification
{
    /**
     * Specification
     *
     * @var Specification
     */
    protected $spec;

    /**
     * Constructs NotSpecification
     *
     * @param Specification $spec The specification
     */
    public function __construct(Specification $spec)
    {
        $this->spec = $spec;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate)
    {
        return !$this->spec->isSatisfiedBy($candidate);
    }
}
