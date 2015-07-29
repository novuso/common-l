<?php

namespace Novuso\Test\Common\Domain\Value\Identifier;

use Novuso\Common\Domain\Value\Identifier\Uri;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Value\Identifier\Uri
 */
class UriTest extends PHPUnit_Framework_TestCase
{
    public function test_that_from_string_returns_expected_instance()
    {
        $uri = Uri::fromString('http://application.com');
        $this->assertSame('http://application.com', $uri->toString());
    }

    public function test_that_parse_returns_expected_instance_scheme()
    {
        $uri = Uri::parse('HTTPS://www.google.com');
        $this->assertSame('https', $uri->scheme());
    }

    public function test_that_parse_returns_expected_instance_authority()
    {
        $uri = Uri::parse('https://username:password@mydomain.net:8110');
        $this->assertSame('username:password@mydomain.net:8110', $uri->authority());
    }

    public function test_that_parse_returns_expected_null_authority()
    {
        $uri = Uri::parse('mailto:smith@example.com');
        $this->assertNull($uri->authority());
    }

    public function test_that_parse_returns_expected_empty_authority()
    {
        $uri = Uri::parse('file:///etc/hosts');
        $this->assertSame('', $uri->authority());
    }

    public function test_that_parse_returns_expected_instance_user_info()
    {
        $uri = Uri::parse('https://username:password@mydomain.net:8110');
        $this->assertSame('username:password', $uri->userInfo());
    }

    public function test_that_parse_returns_expected_null_user_info()
    {
        $uri = Uri::parse('https://mydomain.net:8110');
        $this->assertNull($uri->userInfo());
    }

    public function test_that_parse_returns_expected_instance_ipvfuture()
    {
        $uri = Uri::parse('https://[v1a.1080::8:800:200c:417a]/path');
        $this->assertSame('[v1a.1080::8:800:200c:417a]', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_ipv6()
    {
        $uri = Uri::parse('https://[1080:0:0:0:8:800:200c:417a]/path');
        $this->assertSame('[1080:0:0:0:8:800:200c:417a]', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_ipv4()
    {
        $uri = Uri::parse('https://127.0.0.1/path');
        $this->assertSame('127.0.0.1', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_host()
    {
        $uri = Uri::parse('https://username:password@mydomain.net:8110');
        $this->assertSame('mydomain.net', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_port()
    {
        $uri = Uri::parse('https://username:password@mydomain.net:8110');
        $this->assertSame(8110, $uri->port());
    }

    public function test_that_parse_returns_expected_null_port()
    {
        $uri = Uri::parse('https://username:password@mydomain.net');
        $this->assertNull($uri->port());
    }

    public function test_that_parse_returns_expected_missing_port()
    {
        // URI producers and normalizers should omit the ":" delimiter that
        // separates host from port if the port component is empty
        $uri = Uri::parse('https://username:password@mydomain.net:');
        $this->assertSame('mydomain.net', $uri->host());
    }

    public function test_that_parse_returns_expected_instance_path()
    {
        $uri = Uri::parse('https://application.net/path/to/file.txt');
        $this->assertSame('/path/to/file.txt', $uri->path());
    }

    public function test_that_parse_returns_expected_empty_path()
    {
        $uri = Uri::parse('https://application.net');
        $this->assertSame('', $uri->path());
    }

    public function test_that_parse_returns_expected_instance_query()
    {
        $uri = Uri::parse('https://application.net/path?foo=bar&action=seek');
        $this->assertSame('foo=bar&action=seek', $uri->query());
    }

    public function test_that_parse_returns_expected_null_query()
    {
        $uri = Uri::parse('https://application.net/path');
        $this->assertNull($uri->query());
    }

    public function test_that_parse_returns_expected_empty_query()
    {
        $uri = Uri::parse('https://application.net/path?');
        $this->assertSame('', $uri->query());
    }

    public function test_that_parse_returns_expected_encoded_query()
    {
        $uri = Uri::parse('https://application.net/path?q=foo%2Ebar');
        $this->assertSame('q=foo.bar', $uri->query());
    }

    public function test_that_parse_returns_expected_instance_fragment()
    {
        $uri = Uri::parse('https://application.net/path#section1.03');
        $this->assertSame('section1.03', $uri->fragment());
    }

    public function test_that_parse_returns_expected_null_fragment()
    {
        $uri = Uri::parse('https://application.net/path');
        $this->assertNull($uri->fragment());
    }

    public function test_that_parse_returns_expected_empty_fragment()
    {
        $uri = Uri::parse('https://application.net/path#');
        $this->assertSame('', $uri->fragment());
    }

    public function test_that_parse_returns_expected_encoded_fragment()
    {
        $uri = Uri::parse('https://application.net/path#%3Cfragment%3E');
        $this->assertSame('%3Cfragment%3E', $uri->fragment());
    }

    /**
     * @dataProvider referenceResolutionExamples
     */
    public function test_that_resolve_passes_rfc3986_examples($ref, $expected)
    {
        // http://tools.ietf.org/html/rfc3986#section-5.4
        $base = 'http://a/b/c/d;p?q';
        $uri = Uri::resolve($base, $ref);
        $this->assertSame($expected, $uri->toString());
    }

    public function test_that_resolve_passes_rfc3986_non_strict_with_flag()
    {
        // http://tools.ietf.org/html/rfc3986#section-5.4
        $base = 'http://a/b/c/d;p?q';
        $uri = Uri::resolve($base, 'http:g', false);
        $this->assertSame('http://a/b/c/g', $uri->toString());
    }

    public function test_that_resolve_returns_expected_instance_empty_base_path()
    {
        $base = 'http://app.dev';
        $uri = Uri::resolve($base, '.');
        $this->assertSame('http://app.dev/', $uri->toString());
    }

    public function test_that_resolve_returns_expected_instance_with_null_base_authority()
    {
        $base = 'mailto:smith@example.com';
        $uri = Uri::resolve($base, '.');
        $this->assertSame('mailto:', $uri->toString());
    }

    public function test_that_resolve_returns_expected_instance_with_null_base_authority_2()
    {
        $base = 'mailto:smith@example.com';
        $uri = Uri::resolve($base, './');
        $this->assertSame('mailto:', $uri->toString());
    }

    public function test_that_resolve_returns_expected_instance_with_null_base_authority_3()
    {
        $base = 'mailto:smith@example.com';
        $uri = Uri::resolve($base, '../');
        $this->assertSame('mailto:', $uri->toString());
    }

    public function test_that_from_array_returns_expected_instance()
    {
        $uri = Uri::fromArray([
            'scheme'    => 'http',
            'authority' => 'myapp.com',
            'path'      => '/action',
            'query'     => 'foo=bar',
            'fragment'  => '!wha'
        ]);
        $this->assertSame('http://myapp.com/action?foo=bar#!wha', $uri->toString());
    }

    public function test_that_to_array_returns_expected_value()
    {
        $uri = Uri::parse('http://myapp.com/action?foo=bar#!wha');
        $expected = [
            'scheme'    => 'http',
            'authority' => 'myapp.com',
            'path'      => '/action',
            'query'     => 'foo=bar',
            'fragment'  => '!wha'
        ];
        $this->assertSame($expected, $uri->toArray());
    }

    public function test_that_to_string_returns_user_info()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertSame('https://user:secret@myapp.com:8080/action?foo=bar#!wha', $uri->toString());
    }

    public function test_that_display_does_not_return_user_info()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertSame('https://myapp.com:8080/action?foo=bar#!wha', $uri->display());
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertSame(0, $uri->compareTo($uri));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $uri1 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertSame(0, $uri1->compareTo($uri2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        $uri1 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('http://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertSame(1, $uri1->compareTo($uri2));
    }

    public function test_that_compare_to_returns_one_for_lesser_value()
    {
        $uri1 = Uri::parse('http://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertSame(-1, $uri1->compareTo($uri2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertTrue($uri->equals($uri));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $uri1 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertTrue($uri1->equals($uri2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertFalse($uri->equals('https://user:secret@myapp.com:8080/action?foo=bar#!wha'));
    }

    public function test_that_equals_returns_false_for_different_value()
    {
        $uri1 = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $uri2 = Uri::parse('https://other:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertFalse($uri1->equals($uri2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $uri = Uri::parse('https://user:secret@myapp.com:8080/action?foo=bar#!wha');
        $this->assertSame('https://user:secret@myapp.com:8080/action?foo=bar#!wha', $uri->hashValue());
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_parse_throws_exception_for_missing_scheme()
    {
        Uri::parse('/');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_parse_throws_exception_for_invalid_scheme()
    {
        Uri::parse('ht_tp://www.google.com');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_parse_throws_exception_for_invalid_path()
    {
        Uri::parse('http://app.dev/some[invalid]');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_parse_throws_exception_for_invalid_query()
    {
        Uri::parse('http://app.dev/path?foo="bar"');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_parse_throws_exception_for_invalid_fragment()
    {
        Uri::parse('http://app.dev/path#<foo>');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_parse_throws_exception_for_invalid_user_info()
    {
        Uri::parse('http://user:pa$$wo<r>d@app.dev/path');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_parse_throws_exception_for_invalid_ip_literal_brackets()
    {
        $uri = Uri::parse('http://[3210]123/path');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_resolve_throws_exception_for_ref_with_first_seg_colon()
    {
        Uri::resolve('http://app.dev', '/seg:check/path');
    }

    public function referenceResolutionExamples()
    {
        return [
            ["g:h", "g:h"],
            ["g", "http://a/b/c/g"],
            ["./g", "http://a/b/c/g"],
            ["g/", "http://a/b/c/g/"],
            ["/g", "http://a/g"],
            ["//g", "http://g"],
            ["?y", "http://a/b/c/d;p?y"],
            ["g?y", "http://a/b/c/g?y"],
            ["#s", "http://a/b/c/d;p?q#s"],
            ["g#s", "http://a/b/c/g#s"],
            ["g?y#s", "http://a/b/c/g?y#s"],
            [";x", "http://a/b/c/;x"],
            ["g;x", "http://a/b/c/g;x"],
            ["g;x?y#s", "http://a/b/c/g;x?y#s"],
            ["", "http://a/b/c/d;p?q"],
            [".", "http://a/b/c/"],
            ["./", "http://a/b/c/"],
            ["..", "http://a/b/"],
            ["../", "http://a/b/"],
            ["../g", "http://a/b/g"],
            ["../..", "http://a/"],
            ["../../", "http://a/"],
            ["../../g", "http://a/g"],
            ["../../../g", "http://a/g"],
            ["../../../../g", "http://a/g"],
            ["/./g", "http://a/g"],
            ["/../g", "http://a/g"],
            ["g.", "http://a/b/c/g."],
            [".g", "http://a/b/c/.g"],
            ["g..", "http://a/b/c/g.."],
            ["..g", "http://a/b/c/..g"],
            ["./../g", "http://a/b/g"],
            ["./g/.", "http://a/b/c/g/"],
            ["g/./h", "http://a/b/c/g/h"],
            ["g/../h", "http://a/b/c/h"],
            ["g;x=1/./y", "http://a/b/c/g;x=1/y"],
            ["g;x=1/../y", "http://a/b/c/y"],
            ["g?y/./x", "http://a/b/c/g?y/./x"],
            ["g?y/../x", "http://a/b/c/g?y/../x"],
            ["g#s/./x", "http://a/b/c/g#s/./x"],
            ["g#s/../x", "http://a/b/c/g#s/../x"],
            ["http:g", "http:g"]
        ];
    }
}
