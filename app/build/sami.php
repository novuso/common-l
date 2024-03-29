<?php
/**
 * This file is part of the Novuso Framework
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$paths = require dirname(__DIR__).'/paths.php';

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in([$paths['src'], $paths['vendor'].'/novuso/system/src']);

$options = [
    'theme'                => 'default',
    'title'                => 'Novuso Common API',
    'build_dir'            => $paths['docapi'],
    'cache_dir'            => $paths['cache'].'/sami',
    'default_opened_level' => 1
];

return new Sami($iterator, $options);
