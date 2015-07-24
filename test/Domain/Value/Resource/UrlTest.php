<?php

namespace Novuso\Test\Common\Domain\Value\Resource;

use Novuso\Common\Domain\Value\Resource\Url;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Value\Resource\Uri
 * @covers Novuso\Common\Domain\Value\Resource\Url
 */
class UrlTest extends PHPUnit_Framework_TestCase
{
    public function test_that_parse_returns_expected_instance_with_default_port()
    {
        $url = Url::parse('https://www.google.com:443');
        $this->assertSame('https://www.google.com', $url->toString());
    }

    public function test_that_parse_returns_expected_instance_empty_query()
    {
        $url = Url::parse('https://app.dev?');
        $this->assertSame('', $url->query());
    }

    public function test_that_query_is_normalized_and_ordered_by_key()
    {
        $url1 = Url::parse('https://app.dev?one=two&foo=bar&key=value&=nokey');
        $url2 = Url::parse('https://app.dev?key=value&one=two&foo=bar&');
        $this->assertTrue($url1->equals($url2));
    }
}
