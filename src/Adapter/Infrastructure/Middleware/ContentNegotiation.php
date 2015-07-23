<?php

namespace Novuso\Common\Adapter\Infrastructure\Middleware;

use Exception;
use Negotiation\FormatNegotiator;
use Negotiation\FormatNegotiatorInterface;
use Negotiation\LanguageNegotiator;
use Negotiation\NegotiatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

/**
 * ContentNegotiation provides HTTP content negotiation
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ContentNegotiation implements HttpKernelInterface, TerminableInterface
{
    /**
     * Decorated kernel
     *
     * @var HttpKernelInterface
     */
    protected $kernel;

    /**
     * Format priorities
     *
     * @var string[]
     */
    protected $formatPriorities;

    /**
     * Language priorities
     *
     * @var string[]
     */
    protected $languagePriorities;

    /**
     * Format negotiator
     *
     * @var FormatNegotiatorInterface
     */
    protected $formatNegotiator;

    /**
     * Language negotiator
     *
     * @var NegotiatorInterface
     */
    protected $languageNegotiator;

    /**
     * Constructs ContentNegotiation
     *
     * @param HttpKernelInterface            $kernel             The kernel
     * @param string[]                       $formatPriorities   The format priorities
     * @param string[]                       $languagePriorities The language priorities
     * @param FormatNegotiatorInterface|null $formatNegotiator   The format negotiator
     * @param NegotiatorInterface|null       $languageNegotiator The language negotiator
     */
    public function __construct(
        HttpKernelInterface $kernel,
        array $formatPriorities = [],
        array $languagePriorities = [],
        FormatNegotiatorInterface $formatNegotiator = null,
        NegotiatorInterface $languageNegotiator = null
    ) {
        $this->kernel = $kernel;
        $this->formatPriorities = $formatPriorities;
        $this->languagePriorities = $languagePriorities;
        $this->formatNegotiator = $formatNegotiator ?: new FormatNegotiator();
        $this->languageNegotiator = $languageNegotiator ?: new LanguageNegotiator();
    }

    /**
     * Handles a request to convert it to a response
     *
     * @param Request $request The request
     * @param int     $type    The type of request
     * @param bool    $catch   Whether to catch exceptions or not
     *
     * @return Response
     *
     * @throws Exception When an exception occurs during processing
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $accept = $request->headers->get('Accept');

        if ($accept !== null) {
            $priorities = $this->formatNegotiator->normalizePriorities($this->formatPriorities);
            $accept = $this->formatNegotiator->getBest($accept, $priorities);

            $request->attributes->set('_accept', $accept);

            if ($accept !== null & !$accept->isMediaRange()) {
                $mimeType = $accept->getValue();
                $request->attributes->set('_mime_type', $mimeType);
                $request->attributes->set('_format', $this->formatNegotiator->getFormat($mimeType));
            }
        }

        $acceptLang = $request->headers->get('Accept-Language');

        if ($acceptLang !== null) {
            $acceptLang = $this->languageNegotiator->getBest($acceptLang, $this->languagePriorities);

            $request->attributes->set('_accept_language', $acceptLang);

            if ($acceptLang !== null) {
                $request->attributes->set('_language', $acceptLang->getValue());
            }
        }

        return $this->kernel->handle($request, $type, $catch);
    }

    /**
     * Terminates the request/response cycle
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response)
    {
        if ($this->kernel instanceof TerminableInterface) {
            $this->kernel->terminate($request, $response);
        }
    }
}
