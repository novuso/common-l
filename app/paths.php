<?php
/**
 * This file is part of the Novuso Framework
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */

$root_dir = dirname(__DIR__);

return [
    'root'     => $root_dir,
    'app'      => $root_dir.'/app',
    'bin'      => $root_dir.'/vendor/bin',
    'build'    => $root_dir.'/app/build',
    'cache'    => $root_dir.'/app/cache',
    'docapi'   => $root_dir.'/app/build/api',
    'script'   => $root_dir.'/app/script',
    'src'      => $root_dir.'/src',
    'test'     => $root_dir.'/test',
    'vendor'   => $root_dir.'/vendor'
];
