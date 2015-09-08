<?php
/**
 * This file is part of the Novuso Framework
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */

$paths = require __DIR__.'/paths.php';
$autoload_file = $paths['vendor'].'/autoload.php';

if (!file_exists($autoload_file)) {
    $message = sprintf('Composer install required; missing %s', $autoload_file);
    throw new RuntimeException($message);
}

$loader = require $autoload_file;

return $loader;
