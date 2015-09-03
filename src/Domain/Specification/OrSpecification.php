<?php

namespace Novuso\Common\Domain\Specification;

/**
 * OrSpecification is a logical 'OR' composed of two specifications
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class OrSpecification extends CompositeSpecification
{
    /**
     * First specification
     *
     * @var Specification
     */
    private $firstSpec;

    /**
     * Second specification
     *
     * @var Specification
     */
    private $secondSpec;

    /**
     * Constructs OrSpecification
     *
     * @param Specification $firstSpec  The first specification
     * @param Specification $secondSpec The second specification
     */
    public function __construct(Specification $firstSpec, Specification $secondSpec)
    {
        $this->firstSpec = $firstSpec;
        $this->secondSpec = $secondSpec;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate)
    {
        return $this->firstSpec->isSatisfiedBy($candidate)
               || $this->secondSpec->isSatisfiedBy($candidate);
    }
}
