<?php

namespace Novuso\Test\Common\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\AggregateRootFactory;
use Novuso\System\Type\Type;
use Novuso\Test\Common\Doubles\Domain\EventSourcing\Customer;
use Novuso\Test\Common\Doubles\Domain\EventSourcing\Email;
use Novuso\Test\Common\Doubles\Domain\EventSourcing\ShoppingCart;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\EventSourcing\AggregateRootFactory
 * @covers Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot
 * @covers Novuso\Common\Domain\EventSourcing\EventSourcedDomainEntity
 */
class EventSourcedAggregateRootTest extends PHPUnit_Framework_TestCase
{
    public function test_that_reconstitute_returns_expected_instance()
    {
        $customer = Customer::register(Email::fromString('username@example.com'));
        $customer->changeEmail(Email::fromString('username@gmail.com'));
        $stream = $customer->getRecordedEvents();
        $customer->clearRecordedEvents();
        // reconstitute customer from event stream
        $factory = new AggregateRootFactory();
        $customer = $factory->reconstitute(Type::create(Customer::class), $stream);
        $this->assertSame('username@gmail.com', $customer->email()->toString());
    }

    public function test_that_reconstitute_recursively_calls_child_entities()
    {
        $customer = Customer::register(Email::fromString('username@example.com'));
        $shoppingCart = ShoppingCart::forCustomer($customer);
        $shoppingCart->addItem('Modern PHP');
        $shoppingCart->addItem('PHP in Action');
        $shoppingCart->addItem('Modern PHP');
        $stream = $shoppingCart->getRecordedEvents();
        $shoppingCart->clearRecordedEvents();
        // reconstitute shopping cart from event stream
        $factory = new AggregateRootFactory();
        $shoppingCart = $factory->reconstitute(Type::create(ShoppingCart::class), $stream);
        $items = $shoppingCart->lineItems();
        $this->assertTrue(count($items) === 2 && $items[0]->quantity() === 2);
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_reconstitute_throws_exception_for_invalid_type()
    {
        $customer = Customer::register(Email::fromString('username@example.com'));
        $stream = $customer->getRecordedEvents();
        $customer->clearRecordedEvents();
        $factory = new AggregateRootFactory();
        $email = $factory->reconstitute(Type::create(Email::class), $stream);
    }
}
