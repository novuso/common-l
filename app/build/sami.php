<?php
/**
 * This file is part of the Novuso Framework
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$paths = require dirname(__DIR__).'/paths.php';

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($paths['src']);

$options = [
    'theme'                => 'default',
    'title'                => 'Novuso Common API',
    'build_dir'            => $paths['docapi'],
    'cache_dir'            => $paths['cache'].'/dev/sami',
    'default_opened_level' => 1
];

return new Sami($iterator, $options);
