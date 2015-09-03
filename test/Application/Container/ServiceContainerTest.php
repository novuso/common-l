<?php

namespace Novuso\Test\Common\Application\Container;

use DateTime;
use Novuso\Common\Application\Container\ServiceContainer;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Application\Container\ServiceContainer
 */
class ServiceContainerTest extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = new ServiceContainer();
    }

    public function test_that_factory_defines_an_object_factory()
    {
        $this->container->factory('date', function () {
            return new DateTime('2015-08-17');
        });
        $date1 = $this->container->get('date');
        $date1->modify('+1 day');
        $date2 = $this->container->get('date');
        $this->assertSame('2015-08-17', $date2->format('Y-m-d'));
    }

    public function test_that_service_defines_a_shared_service()
    {
        $this->container->service('date', function () {
            return new DateTime('2015-08-17');
        });
        $date1 = $this->container->get('date');
        $date1->modify('+1 day');
        $date2 = $this->container->get('date');
        $this->assertSame('2015-08-18', $date2->format('Y-m-d'));
    }

    public function test_that_remove_correctly_removes_service()
    {
        $this->container->service('date', function () {
            return new DateTime('2015-08-17');
        });
        $this->container->remove('date');
        $this->assertFalse($this->container->has('date'));
    }

    public function test_that_parameters_can_be_set_for_configuration()
    {
        $this->container->setParameter('date', '2015-08-17');
        $this->container->factory('date', function ($container) {
            return new DateTime($container->getParameter('date'));
        });
        $date = $this->container->get('date');
        $this->assertSame('2015-08-17', $date->format('Y-m-d'));
    }

    public function test_that_get_parameter_returns_null_with_undefined_name_by_default()
    {
        $this->assertNull($this->container->getParameter('foo'));
    }

    public function test_that_get_parameter_returns_custom_default_with_undefined_name()
    {
        $this->assertTrue($this->container->getParameter('enabled', true));
    }

    public function test_that_remove_parameter_correctly_removes_parameter()
    {
        $this->container->setParameter('date', '2015-08-17');
        $this->container->removeParameter('date');
        $this->assertFalse($this->container->hasParameter('date'));
    }

    /**
     * @expectedException Novuso\Common\Application\Container\Exception\EntryNotFoundException
     */
    public function test_that_get_throws_exception_for_invalid_entry()
    {
        $this->container->get('date');
    }
}
