<?php

namespace Novuso\Common\Domain\Value\Identifier;

use Novuso\Common\Domain\Value\ValueObject;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Type\Comparable;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * Uri represents a uniform resource identifier
 *
 * @see       http://tools.ietf.org/html/rfc3986
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class Uri extends ValueObject implements Comparable
{
    /**
     * URI capture pattern
     *
     * This is a variation on the capture pattern in RFC 3986 - appendix B.
     *
     * @see http://tools.ietf.org/html/rfc3986#appendix-B
     *
     * @var string
     */
    const URI_PATTERN = '/\A(?:([^:\/?#]+)(:))?(?:(\/\/)([^\/?#]*))?([^?#]*)(?:(\?)([^#]*))?(?:(#)(.*))?\z/';

    /**
     * Authority capture pattern
     *
     * This pattern is used to capture authority subcomponents.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2
     *
     * @var string
     */
    const AUTHORITY_PATTERN = '/\A(?:([^@]*)@)?(\[[^\]]*\]|[^:]*)(?::(\d*))?\z/';

    /**
     * Scheme validation pattern
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.1
     *
     * @var string
     */
    const SCHEME_PATTERN = '/\A[a-z][a-z0-9+.\-]*\z/i';

    /**
     * Percent encoded characters
     *
     * @see http://tools.ietf.org/html/rfc3986#section-2.1
     *
     * @var string
     */
    const PCT_ENCODED_SET = '%[a-fA-F0-9]{2}';

    /**
     * General delimiters
     *
     * @see http://tools.ietf.org/html/rfc3986#section-2.2
     *
     * @var string
     */
    const GEN_DELIMS_SET = ':\/\?#\[\]@';

    /**
     * Subcomponent delimiters
     *
     * @see http://tools.ietf.org/html/rfc3986#section-2.2
     *
     * @var string
     */
    const SUB_DELIMS_SET = '!$&\'()*+,;=';

    /**
     * Set of unreserved characters
     *
     * @see http://tools.ietf.org/html/rfc3986#section-2.3
     *
     * @var string
     */
    const UNRESERVED_SET = 'a-zA-Z0-9\-._~';

    /**
     * Default ports
     *
     * @var array
     */
    protected static $defaultPorts = [];

    /**
     * Scheme
     *
     * @var string
     */
    protected $scheme;

    /**
     * Authority
     *
     * @var string|null
     */
    protected $authority;

    /**
     * Path
     *
     * @var string
     */
    protected $path;

    /**
     * Query
     *
     * @var string|null
     */
    protected $query;

    /**
     * Fragment
     *
     * @var string|null
     */
    protected $fragment;

    /**
     * User info
     *
     * @var string|null
     */
    protected $userInfo;

    /**
     * Host
     *
     * @var string|null
     */
    protected $host;

    /**
     * Port
     *
     * @var int|null
     */
    protected $port;

    /**
     * Constructs Uri
     *
     * @internal
     *
     * @param string|null $scheme    The scheme
     * @param string|null $authority The authority
     * @param string      $path      The path
     * @param string|null $query     The query
     * @param string|null $fragment  The fragment
     *
     * @throws DomainException When values are invalid
     */
    protected function __construct($scheme, $authority, $path, $query, $fragment)
    {
        $auth = static::parseAuthority($authority);
        $this->userInfo = static::normalizeUserInfo($auth['userInfo']);
        $this->host = static::normalizeHost($auth['host']);
        $this->port = static::normalizePort($auth['port'], $scheme);
        $this->authority = static::buildAuthority($this->userInfo, $this->host, $this->port);
        $this->scheme = static::normalizeScheme($scheme);
        $this->path = static::normalizePath($path);
        $this->query = static::normalizeQuery($query);
        $this->fragment = static::normalizeFragment($fragment);
    }

    /**
     * Creates instance from a URI string
     *
     * @param string $uri A URI string
     *
     * @return Uri
     *
     * @throws TypeException When uri is not a string
     * @throws DomainException When the URI string is invalid
     */
    public static function parse($uri)
    {
        if (!is_string($uri)) {
            $message = sprintf(
                '%s expects $uri to be a string; received (%s) %s',
                __METHOD__,
                gettype($uri),
                VarPrinter::toString($uri)
            );
            throw TypeException::create($message);
        }

        preg_match(self::URI_PATTERN, $uri, $matches);

        $components = static::componentsFromMatches($matches);
        $scheme = $components['scheme'];
        $authority = $components['authority'];
        $path = $components['path'];
        $query = $components['query'];
        $fragment = $components['fragment'];

        return new static($scheme, $authority, $path, $query, $fragment);
    }

    /**
     * Creates instance from a URI string
     *
     * @param string $state The string representation
     *
     * @return Uri
     *
     * @throws TypeException When state is not a string
     * @throws DomainException When the string is invalid
     */
    public static function fromString($state)
    {
        return static::parse($state);
    }

    /**
     * Creates instance from a base URI and relative reference
     *
     * @SuppressWarnings(PHPMD)
     *
     * @see http://tools.ietf.org/html/rfc3986#section-5.2
     *
     * @param Uri|string $base      A Uri instance or string
     * @param string     $reference A relative URI reference
     * @param bool       $strict    Whether or not to enable strict parsing
     *
     * @return Uri
     *
     * @throws TypeException When base or reference are invalid types
     * @throws DomainException When the base or reference are invalid
     */
    public static function resolve($base, $reference, $strict = true)
    {
        if (!($base instanceof self)) {
            $base = static::parse($base);
        }

        if (!is_string($reference)) {
            $message = sprintf(
                '%s expects $reference to be a string; received (%s) %s',
                __METHOD__,
                gettype($reference),
                VarPrinter::toString($reference)
            );
            throw TypeException::create($message);
        }

        preg_match(self::URI_PATTERN, $reference, $matches);
        $ref = static::componentsFromMatches($matches);

        // http://tools.ietf.org/html/rfc3986#section-5.2.2
        // A non-strict parser may ignore a scheme in the reference if it is
        // identical to the base URI's scheme
        if (!$strict && ($ref['scheme'] !== null && $base->scheme() === $ref['scheme'])) {
            $ref['scheme'] = null;
        }

        if ($ref['scheme'] !== null) {
            $scheme = $ref['scheme'];
            $authority = $ref['authority'];
            $path = static::removeDotSegments($ref['path']);
            $query = $ref['query'];
        } else {
            // http://tools.ietf.org/html/rfc3986#section-3.3
            // In addition, a URI reference (Section 4.1) may be a
            // relative-path reference, in which case the first path segment
            // cannot contain a colon (":") character.
            // START: extra check for colon in first segment
            $segments = explode('/', trim($ref['path'], '/'));
            if (isset($segments[0]) && strpos($segments[0], ':') !== false) {
                $message = sprintf('First segment in reference (%s) cannot contain a colon (":")', $reference);
                throw DomainException::create($message);
            }
            // END: extra check for colon in first segment
            if ($ref['authority'] !== null) {
                $authority = $ref['authority'];
                $path = static::removeDotSegments($ref['path']);
                $query = $ref['query'];
            } else {
                if ($ref['path'] === '') {
                    $path = $base->path();
                    if ($ref['query'] !== null) {
                        $query = $ref['query'];
                    } else {
                        $query = $base->query();
                    }
                } else {
                    if ($ref['path'][0] === '/') {
                        $path = static::removeDotSegments($ref['path']);
                    } else {
                        $path = static::mergePaths($base, $ref['path']);
                        $path = static::removeDotSegments($path);
                    }
                    $query = $ref['query'];
                }
                $authority = $base->authority();
            }
            $scheme = $base->scheme();
        }
        $fragment = $ref['fragment'];

        return new static($scheme, $authority, $path, $query, $fragment);
    }

    /**
     * Creates instance from components
     *
     * The following key names should hold their respective values:
     *
     * * scheme
     * * authority
     * * path
     * * query
     * * fragment
     *
     * @SuppressWarnings(PHPMD)
     *
     * @param array $components The components
     *
     * @return Uri
     *
     * @throws DomainException When components are missing or invalid
     */
    public static function fromArray(array $components)
    {
        $scheme = isset($components['scheme']) ? $components['scheme'] : null;
        $authority = isset($components['authority']) ? $components['authority'] : null;
        $path = isset($components['path']) ? $components['path'] : '';
        $query = isset($components['query']) ? $components['query'] : null;
        $fragment = isset($components['fragment']) ? $components['fragment'] : null;

        return new static($scheme, $authority, $path, $query, $fragment);
    }

    /**
     * Retrieves the scheme
     *
     * @return string
     */
    public function scheme()
    {
        return $this->scheme;
    }

    /**
     * Retrieves the authority
     *
     * @return string|null
     */
    public function authority()
    {
        return $this->authority;
    }

    /**
     * Retrieves the path
     *
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Retrieves the query
     *
     * @return string|null
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Retrieves the fragment
     *
     * @return string|null
     */
    public function fragment()
    {
        return $this->fragment;
    }

    /**
     * Retrieves the user info
     *
     * @return string|null
     */
    public function userInfo()
    {
        return $this->userInfo;
    }

    /**
     * Retrieves the host
     *
     * @return string|null
     */
    public function host()
    {
        return $this->host;
    }

    /**
     * Retrieves the port
     *
     * @return int|null
     */
    public function port()
    {
        return $this->port;
    }

    /**
     * Retrieves an array representation
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'scheme'    => $this->scheme,
            'authority' => $this->authority,
            'path'      => $this->path,
            'query'     => $this->query,
            'fragment'  => $this->fragment
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        $output = sprintf('%s:', $this->scheme);
        if ($this->authority !== null) {
            $output .= sprintf('//%s', $this->authority);
        }
        $output .= $this->path;
        if ($this->query !== null) {
            $output .= sprintf('?%s', $this->query);
        }
        if ($this->fragment !== null) {
            $output .= sprintf('#%s', $this->fragment);
        }

        return $output;
    }

    /**
     * Retrieves string representation without user info
     *
     * @return string
     */
    public function display()
    {
        $output = sprintf('%s:', $this->scheme);
        if ($this->authority !== null) {
            $output .= sprintf('//%s', $this->host);
            if ($this->port !== null) {
                $output .= sprintf(':%d', $this->port);
            }
        }
        $output .= $this->path;
        if ($this->query !== null) {
            $output .= sprintf('?%s', $this->query);
        }
        if ($this->fragment !== null) {
            $output .= sprintf('#%s', $this->fragment);
        }

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object)
    {
        if ($this === $object) {
            return 0;
        }

        assert(
            Test::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

        $comp = strnatcmp($this->toString(), $object->toString());

        if ($comp > 0) {
            return 1;
        }
        if ($comp < 0) {
            return -1;
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object)
    {
        if ($this === $object) {
            return true;
        }

        if (!Test::areSameType($this, $object)) {
            return false;
        }

        return $this->toString() === $object->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue()
    {
        return $this->toString();
    }

    /**
     * Exchanges URI_PATTERN matches for components
     *
     * @SuppressWarnings(PHPMD)
     *
     * @param array $matches The regex matches
     *
     * @return array
     */
    protected static function componentsFromMatches(array $matches)
    {
        // http://tools.ietf.org/html/rfc3986#section-5.3
        // Note that we are careful to preserve the distinction between a
        // component that is undefined, meaning that its separator was not
        // present in the reference, and a component that is empty, meaning
        // that the separator was present and was immediately followed by the
        // next component separator or the end of the reference.
        if (isset($matches[2]) && $matches[2]) {
            $scheme = isset($matches[1]) ? $matches[1] : '';
        } else {
            $scheme = null;
        }
        if (isset($matches[3]) && $matches[3]) {
            $authority = isset($matches[4]) ? $matches[4] : '';
        } else {
            $authority = null;
        }
        $path = isset($matches[5]) ? $matches[5] : '';
        if (isset($matches[6]) && $matches[6]) {
            $query = isset($matches[7]) ? $matches[7] : '';
        } else {
            $query = null;
        }
        if (isset($matches[8]) && $matches[8]) {
            $fragment = isset($matches[9]) ? $matches[9] : '';
        } else {
            $fragment = null;
        }

        return [
            'scheme'    => $scheme,
            'authority' => $authority,
            'path'      => $path,
            'query'     => $query,
            'fragment'  => $fragment
        ];
    }

    /**
     * Parses authority component into parts
     *
     * @SuppressWarnings(PHPMD)
     *
     * @param string|null $authority The authority
     *
     * @return array
     */
    protected static function parseAuthority($authority)
    {
        if ($authority === null) {
            return [
                'userInfo' => null,
                'host'     => null,
                'port'     => null
            ];
        }

        preg_match(self::AUTHORITY_PATTERN, $authority, $matches);

        $userInfo = isset($matches[1]) && $matches[1] ? $matches[1] : null;
        $host = isset($matches[2]) && $matches[2] ? $matches[2] : '';
        $port = isset($matches[3]) && $matches[3] ? ((int) $matches[3]) : null;

        return [
            'userInfo' => $userInfo,
            'host'     => $host,
            'port'     => $port
        ];
    }

    /**
     * Builds authority from parts
     *
     * @param string|null $userInfo The user info
     * @param string|null $host     The host
     * @param int|null    $port     The port
     *
     * @return string|null
     */
    protected static function buildAuthority($userInfo, $host, $port)
    {
        if ($host === null) {
            return null;
        }

        $authority = '';

        if ($userInfo !== null) {
            $authority .= sprintf('%s@', $userInfo);
        }
        $authority .= $host;
        if ($port !== null) {
            $authority .= sprintf(':%d', $port);
        }

        return $authority;
    }

    /**
     * Validates and normalizes the scheme
     *
     * @param string|null $scheme The scheme
     *
     * @return string
     *
     * @throws DomainException When the scheme is invalid
     */
    protected static function normalizeScheme($scheme)
    {
        if (!static::isValidScheme($scheme)) {
            $message = sprintf('Invalid URI scheme: %s', VarPrinter::toString($scheme));
            throw DomainException::create($message);
        }

        return strtolower($scheme);
    }

    /**
     * Validates and normalizes the path
     *
     * @param string $path The path
     *
     * @return string
     *
     * @throws DomainException When the path is invalid
     */
    protected static function normalizePath($path)
    {
        if (!static::isValidPath($path)) {
            $message = sprintf('Invalid URI path: %s', VarPrinter::toString($path));
            throw DomainException::create($message);
        }

        $path = static::removeDotSegments($path);

        return static::encodePath(static::decode($path, self::UNRESERVED_SET));
    }

    /**
     * Validates and normalizes the query
     *
     * @param string|null $query The query
     *
     * @return string|null
     *
     * @throws DomainException When the query is invalid
     */
    protected static function normalizeQuery($query)
    {
        if ($query === null) {
            return null;
        }

        if (!static::isValidQuery($query)) {
            $message = sprintf('Invalid URI query: %s', VarPrinter::toString($query));
            throw DomainException::create($message);
        }

        return static::encodeQuery(static::decode($query, self::UNRESERVED_SET));
    }

    /**
     * Validates and normalizes the fragment
     *
     * @param string|null $fragment The fragment
     *
     * @return string|null
     *
     * @throws DomainException When the fragment is invalid
     */
    protected static function normalizeFragment($fragment)
    {
        if ($fragment === null) {
            return null;
        }

        if (!static::isValidFragment($fragment)) {
            $message = sprintf('Invalid URI fragment: %s', VarPrinter::toString($fragment));
            throw DomainException::create($message);
        }

        return static::encodeFragment(static::decode($fragment, self::UNRESERVED_SET));
    }

    /**
     * Validates and normalizes the user info
     *
     * @param string|null $userInfo The user info
     *
     * @return string|null
     *
     * @throws DomainException When the user info is invalid
     */
    protected static function normalizeUserInfo($userInfo)
    {
        if ($userInfo === null) {
            return null;
        }

        if (!static::isValidUserInfo($userInfo)) {
            $message = sprintf('Invalid user info: %s', VarPrinter::toString($userInfo));
            throw DomainException::create($message);
        }

        return static::encodeUserInfo(static::decode($userInfo, self::UNRESERVED_SET));
    }

    /**
     * Validates and normalizes the host
     *
     * @param string|null $host The host
     *
     * @return string|null
     *
     * @throws DomainException When the host is invalid
     */
    protected static function normalizeHost($host)
    {
        if ($host === null) {
            return null;
        }

        if ($host === '') {
            return '';
        }

        if (!static::isValidHost($host)) {
            $message = sprintf('Invalid host: %s', VarPrinter::toString($host));
            throw DomainException::create($message);
        }

        // Although host is case-insensitive, producers and normalizers should
        // use lowercase for registered names and hexadecimal addresses for the
        // sake of uniformity, while only using uppercase letters for
        // percent-encodings.
        $host = mb_strtolower($host, 'UTF-8');

        return static::encodeHost(static::decode($host, static::UNRESERVED_SET));
    }

    /**
     * Validates and normalizes the port
     *
     * @param int|null    $port The port
     * @param string|null $scheme The scheme
     *
     * @return int|null
     *
     * @throws DomainException When the port is invalid
     */
    protected static function normalizePort($port, $scheme)
    {
        if ($port === null) {
            return null;
        }

        if ($scheme && isset(static::$defaultPorts[$scheme]) && ($port == static::$defaultPorts[$scheme])) {
            return null;
        }

        return $port;
    }

    /**
     * Encodes the path
     *
     * @param string $path The path
     *
     * @return string
     */
    protected static function encodePath($path)
    {
        // http://tools.ietf.org/html/rfc3986#section-3.3
        // path          = path-abempty    ; begins with "/" or is empty
        //               / path-absolute   ; begins with "/" but not "//"
        //               / path-noscheme   ; begins with a non-colon segment
        //               / path-rootless   ; begins with a segment
        //               / path-empty      ; zero characters
        //
        // path-abempty  = *( "/" segment )
        // path-absolute = "/" [ segment-nz *( "/" segment ) ]
        // path-noscheme = segment-nz-nc *( "/" segment )
        // path-rootless = segment-nz *( "/" segment )
        // path-empty    = 0<pchar>
        // segment       = *pchar
        // segment-nz    = 1*pchar
        // segment-nz-nc = 1*( unreserved / pct-encoded / sub-delims / "@" )
        //               ; non-zero-length segment without any colon ":"
        // pchar         = unreserved / pct-encoded / sub-delims / ":" / "@"
        $excluded = self::UNRESERVED_SET.self::SUB_DELIMS_SET.':@\/';

        return static::encode($path, $excluded);
    }

    /**
     * Encodes the query
     *
     * @param string $query The query
     *
     * @return string
     */
    protected static function encodeQuery($query)
    {
        // http://tools.ietf.org/html/rfc3986#section-3.4
        // query = *( pchar / "/" / "?" )
        // pchar = unreserved / pct-encoded / sub-delims / ":" / "@"
        $excluded = self::UNRESERVED_SET.self::SUB_DELIMS_SET.':@\/\?';

        return static::encode($query, $excluded);
    }

    /**
     * Encodes the fragment
     *
     * @param string $fragment The fragment
     *
     * @return string
     */
    protected static function encodeFragment($fragment)
    {
        // http://tools.ietf.org/html/rfc3986#section-3.5
        // fragment = *( pchar / "/" / "?" )
        // pchar = unreserved / pct-encoded / sub-delims / ":" / "@"
        $excluded = self::UNRESERVED_SET.self::SUB_DELIMS_SET.':@\/\?';

        return static::encode($fragment, $excluded);
    }

    /**
     * Encodes the user info
     *
     * @param string $userInfo The user info
     *
     * @return string
     */
    protected static function encodeUserInfo($userInfo)
    {
        // http://tools.ietf.org/html/rfc3986#section-3.2.1
        // userinfo = *( unreserved / pct-encoded / sub-delims / ":" )
        $excluded = self::UNRESERVED_SET.self::SUB_DELIMS_SET.':';

        return static::encode($userInfo, $excluded);
    }

    /**
     * Encodes the host
     *
     * @param string $host The host
     *
     * @return string
     */
    protected static function encodeHost($host)
    {
        // http://tools.ietf.org/html/rfc3986#section-3.2.2
        // IP-literal = "[" ( IPv6address / IPvFuture  ) "]"
        // IPvFuture  = "v" 1*HEXDIG "." 1*( unreserved / sub-delims / ":" )
        if ($host[0] === '[') {
            $excluded = self::UNRESERVED_SET.self::SUB_DELIMS_SET.'\[\]:';

            return static::encode($host, $excluded);
        }

        // http://tools.ietf.org/html/rfc3986#section-3.2.2
        // NOTE: characters in IPv4 address are all in the unreserved set
        // IPv4address = dec-octet "." dec-octet "." dec-octet "." dec-octet
        // reg-name    = *( unreserved / pct-encoded / sub-delims )
        $excluded = self::UNRESERVED_SET.self::SUB_DELIMS_SET;

        return static::encode($host, $excluded);
    }

    /**
     * Encodes a component
     *
     * @codeCoverageIgnore
     *
     * @param string $component The component
     * @param string $excluded  The set of excluded characters
     *
     * @return string
     */
    protected static function encode($component, $excluded)
    {
        return preg_replace_callback(self::encodingRegex($excluded), function ($matches) {
            return rawurlencode($matches[0]);
        }, $component);
    }

    /**
     * Decodes a component
     *
     * @param string $component The component
     * @param string $allowed   The set of allowed characters
     *
     * @return string
     */
    protected static function decode($component, $allowed)
    {
        $allowed = sprintf('/[%s]/', $allowed);
        $encoded = sprintf('/%s/', self::PCT_ENCODED_SET);

        return preg_replace_callback($encoded, function ($matches) use ($allowed) {
            $char = rawurldecode($matches[0]);

            if (preg_match($allowed, $char)) {
                return $char;
            }

            return strtoupper($matches[0]);
        }, $component);
    }

    /**
     * Removes dot segments
     *
     * Algorithm based on section 5.2.4 of RFC 3986.
     *
     * @SuppressWarnings(PHPMD)
     *
     * @see http://tools.ietf.org/html/rfc3986#section-5.2.4
     *
     * @param string $path The input path
     *
     * @return string
     */
    protected static function removeDotSegments($path)
    {
        $output = '';
        while ($path) {
            if ('..' == $path || '.' == $path) {
                break;
            }
            switch (true) {
                case ('./' == substr($path, 0, 2)):
                    $path = substr($path, 2);
                    break;
                case ('../' == substr($path, 0, 3)):
                    $path = substr($path, 3);
                    break;
                case ('/./' == substr($path, 0, 3)):
                    $path = substr($path, 2);
                    break;
                case ('/../' == substr($path, 0, 4)):
                    $path = '/'.substr($path, 4);
                    $pos = strrpos($output, '/', -1);
                    if ($pos !== false) {
                        $output = substr($output, 0, $pos);
                    }
                    break;
                case ('/..' == substr($path, 0, 3) && (in_array(substr($path, 3, 1), [false, '', '/'], true))):
                    $path = '/'.substr($path, 3);
                    $pos = strrpos($output, '/', -1);
                    if ($pos !== false) {
                        $output = substr($output, 0, $pos);
                    }
                    break;
                case ('/.' == substr($path, 0, 2) && (in_array(substr($path, 2, 1), [false, '', '/'], true))):
                    $path = '/'.substr($path, 2);
                    break;
                default:
                    $nextSlash = strpos($path, '/', 1);
                    if (false === $nextSlash) {
                        $segment = $path;
                    } else {
                        $segment = substr($path, 0, $nextSlash);
                    }
                    $output .= $segment;
                    $path = substr($path, strlen($segment));
                    break;
            }
        }

        return $output;
    }

    /**
     * Merges a base URI and relative path
     *
     * @see http://tools.ietf.org/html/rfc3986#section-5.2.3
     *
     * @param Uri    $baseUri  The base Uri instance
     * @param string $relative The relative path
     *
     * @return string
     */
    protected static function mergePaths(Uri $baseUri, $relative)
    {
        $basePath = $baseUri->path();

        if ($baseUri->authority() !== null && $basePath === '') {
            return sprintf('/%s', $relative);
        }

        $last = strrpos($basePath, '/');

        if ($last !== false) {
            return sprintf('%s/%s', substr($basePath, 0, $last), $relative);
        }

        return $relative;
    }

    /**
     * Checks if a scheme is valid
     *
     * @param string|null $scheme The scheme
     *
     * @return bool
     */
    protected static function isValidScheme($scheme)
    {
        // http://tools.ietf.org/html/rfc3986#section-3
        // The scheme and path components are required, though the path may be
        // empty (no characters)
        if ($scheme === null || $scheme === '') {
            return false;
        }

        return !!preg_match(static::SCHEME_PATTERN, $scheme);
    }

    /**
     * Checks if a path is valid
     *
     * @param string $path The path
     *
     * @return bool
     */
    protected static function isValidPath($path)
    {
        // http://tools.ietf.org/html/rfc3986#section-3
        // The scheme and path components are required, though the path may be
        // empty (no characters)
        if ($path === '') {
            return true;
        }

        // http://tools.ietf.org/html/rfc3986#section-3.3
        // path          = path-abempty    ; begins with "/" or is empty
        //               / path-absolute   ; begins with "/" but not "//"
        //               / path-noscheme   ; begins with a non-colon segment
        //               / path-rootless   ; begins with a segment
        //               / path-empty      ; zero characters
        //
        // path-abempty  = *( "/" segment )
        // path-absolute = "/" [ segment-nz *( "/" segment ) ]
        // path-noscheme = segment-nz-nc *( "/" segment )
        // path-rootless = segment-nz *( "/" segment )
        // path-empty    = 0<pchar>
        // segment       = *pchar
        // segment-nz    = 1*pchar
        // segment-nz-nc = 1*( unreserved / pct-encoded / sub-delims / "@" )
        //               ; non-zero-length segment without any colon ":"
        // pchar         = unreserved / pct-encoded / sub-delims / ":" / "@"
        $pattern = sprintf(
            '/\A(?:(?:[%s%s:@]|(?:%s))*\/?)*\z/',
            self::UNRESERVED_SET,
            self::SUB_DELIMS_SET,
            self::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $path);
    }

    /**
     * Checks if a query is valid
     *
     * @param string|null $query The query
     *
     * @return bool
     */
    protected static function isValidQuery($query)
    {
        if ($query === '') {
            return true;
        }

        // http://tools.ietf.org/html/rfc3986#section-3.4
        // query = *( pchar / "/" / "?" )
        // pchar = unreserved / pct-encoded / sub-delims / ":" / "@"
        $pattern = sprintf(
            '/\A(?:[%s%s\/?:@]|(?:%s))*\z/',
            self::UNRESERVED_SET,
            self::SUB_DELIMS_SET,
            self::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $query);
    }

    /**
     * Checks if a fragment is valid
     *
     * @param string|null $fragment The fragment
     *
     * @return bool
     */
    protected static function isValidFragment($fragment)
    {
        if ($fragment === '') {
            return true;
        }

        // http://tools.ietf.org/html/rfc3986#section-3.5
        // fragment = *( pchar / "/" / "?" )
        // pchar = unreserved / pct-encoded / sub-delims / ":" / "@"
        $pattern = sprintf(
            '/\A(?:[%s%s\/?:@]|(?:%s))*\z/',
            static::UNRESERVED_SET,
            static::SUB_DELIMS_SET,
            static::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $fragment);
    }

    /**
     * Checks if user info is valid
     *
     * @param string|null $userInfo The user info
     *
     * @return bool
     */
    protected static function isValidUserInfo($userInfo)
    {
        // http://tools.ietf.org/html/rfc3986#section-3.2.1
        // userinfo = *( unreserved / pct-encoded / sub-delims / ":" )
        $pattern = sprintf(
            '/\A(?:[%s%s:]|(?:%s))*\z/',
            self::UNRESERVED_SET,
            self::SUB_DELIMS_SET,
            self::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $userInfo);
    }

    /**
     * Checks if a host is valid
     *
     * @param string|null $host The host
     *
     * @return bool
     */
    protected static function isValidHost($host)
    {
        // http://tools.ietf.org/html/rfc3986#section-3.2.2
        // A host identified by an Internet Protocol literal address, version 6
        // [RFC3513] or later, is distinguished by enclosing the IP literal
        // within square brackets ("[" and "]").  This is the only place where
        // square bracket characters are allowed in the URI syntax.
        if (strpos($host, '[') !== false) {
            return static::isValidIpLiteral($host);
        }

        // IPv4address = dec-octet "." dec-octet "." dec-octet "." dec-octet
        $dec = '(?:(?:2[0-4]|1[0-9]|[1-9])?[0-9]|25[0-5])';
        $ipV4 = sprintf('/\A(?:%s\.){3}%s\z/', $dec, $dec);
        if (preg_match($ipV4, $host)) {
            return true;
        }

        // reg-name = *( unreserved / pct-encoded / sub-delims )
        $pattern = sprintf(
            '/\A(?:[%s%s]|(?:%s))*\z/',
            self::UNRESERVED_SET,
            self::SUB_DELIMS_SET,
            self::PCT_ENCODED_SET
        );

        return !!preg_match($pattern, $host);
    }

    /**
     * Checks if a IP literal is valid
     *
     * @param string $ip The IP literal
     *
     * @return bool
     */
    protected static function isValidIpLiteral($ip)
    {
        // outer brackets
        $length = strlen($ip);
        if ($ip[0] !== '[' || $ip[$length - 1] !== ']') {
            return false;
        }

        // remove brackets
        $ip = substr($ip, 1, $length - 2);

        // starts with "v" (case-insensitive)
        // IPvFuture = "v" 1*HEXDIG "." 1*( unreserved / sub-delims / ":" )
        $pattern = sprintf(
            '/\A[v](?:[a-f0-9]+)\.[%s%s:]+\z/i',
            self::UNRESERVED_SET,
            self::SUB_DELIMS_SET
        );
        if (preg_match($pattern, $ip)) {
            return true;
        }

        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Provides the encoding regex to prevent double encoding
     *
     * @param string $excluded The set of excluded characters
     *
     * @return string
     */
    private static function encodingRegex($excluded)
    {
        return sprintf('/(?:[^%s%%]+|%%(?![a-fA-F0-9]{2}))/', $excluded);
    }
}
