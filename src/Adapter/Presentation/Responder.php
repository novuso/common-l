<?php

namespace Novuso\Common\Adapter\Presentation;

use Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Responder is the base class for an HTTP response formatter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class Responder
{
    /**
     * Formats a view into a response
     *
     * @param View $view The view
     *
     * @return Response
     *
     * @throws Exception When unable to format a response
     */
    abstract public function format(View $view);

    /**
     * Formats a template path
     *
     * @param string $name      The template name
     * @param string $format    The template format
     * @param string $extension The template extension
     *
     * @return string
     */
    public function template($name, $format = 'html', $extension = 'twig')
    {
        return sprintf('%s.%s.%s', str_replace(':', '/', $name), $format, $extension);
    }

    /**
     * Creates a JSON response
     *
     * @param mixed  $data    The response data
     * @param int    $status  The status code
     * @param array  $headers An array of response headers
     *
     * @return JsonResponse
     */
    public function jsonResponse($data, $status = Response::HTTP_OK, array $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * Creates a file response
     *
     * Options and defaults:
     *
     *     $options = [
     *         'public'             => true,
     *         'contentDisposition' => null,
     *         'autoEtag'           => false,
     *         'autoLastModified'   => true
     *     ];
     *
     * @param SplFileInfo|string $file    The file to stream
     * @param int                $status  The status code
     * @param array              $headers An array of response headers
     * @param array              $options Additional options
     *
     * @return BinaryFileResponse
     */
    public function fileResponse($file, $status = Response::HTTP_OK, array $headers = [], array $options = [])
    {
        $options = array_merge([
            'public'             => true,
            'contentDisposition' => null,
            'autoEtag'           => false,
            'autoLastModified'   => true
        ], $options);

        return new BinaryFileResponse(
            $file,
            $status,
            $headers,
            $options['public'],
            $options['contentDisposition'],
            $options['autoEtag'],
            $options['autoLastModified']
        );
    }

    /**
     * Creates a redirect response
     *
     * @param string $url     The URL to redirect to
     * @param int    $status  The status code
     * @param array  $headers An array of response headers
     *
     * @return RedirectResponse
     */
    public function redirectResponse($url, $status = Response::HTTP_FOUND, array $headers = [])
    {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * Creates a streamed response
     *
     * @param callable $callback A callback function to echo content
     * @param int      $status   The status code
     * @param array    $headers  An array of response headers
     *
     * @return StreamedResponse
     */
    public function streamResponse(callable $callback, $status = Response::HTTP_OK, array $headers = [])
    {
        return new StreamedResponse($callback, $status, $headers);
    }
}
