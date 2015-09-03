<?php

namespace Novuso\Common\Domain\Specification;

/**
 * CompositeSpecification is the base class for composite specifications
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class CompositeSpecification implements Specification
{
    /**
     * {@inheritdoc}
     */
    abstract public function isSatisfiedBy($candidate);

    /**
     * {@inheritdoc}
     */
    public function andIf(Specification $other)
    {
        return new AndSpecification($this, $other);
    }

    /**
     * {@inheritdoc}
     */
    public function orIf(Specification $other)
    {
        return new OrSpecification($this, $other);
    }

    /**
     * {@inheritdoc}
     */
    public function not()
    {
        return new NotSpecification($this);
    }
}
