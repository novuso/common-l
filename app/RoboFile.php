<?php

use Robo\Tasks;
use Symfony\Component\Finder\Finder;

/**
 * RoboFile is the task runner for this project
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class RoboFile extends Tasks
{
    /**
     * Application paths
     *
     * Access with getPaths()
     *
     * @var array
     */
    private $paths;

    //===================================================//
    // Main Targets                                      //
    //===================================================//

    /**
     * The default build process
     */
    public function build()
    {
        $this->info('Starting build');
        $this->dirPrepare();
        $this->phpLint();
        $this->phpCodeStyle();
        $this->phpMessDetect();
        $this->phpTest();
        $this->docsPhpApi(['force' => true]);
        $this->info('build complete');
    }

    /**
     * Installs project dependencies
     *
     * @param array $opts The options
     *
     * @option $prod Optimize for production
     */
    public function install($opts = ['prod' => false])
    {
        $prod = isset($opts['prod']) && $opts['prod'] ? true : false;
        $this->info('Installing project dependencies');
        $this->composerInstall(['prod' => $prod]);
        $this->info('Project dependencies installed');
    }

    /**
     * Updates project dependencies
     *
     * @param array $opts The options
     *
     * @option $prod Optimize for production
     */
    public function update($opts = ['prod' => false])
    {
        $prod = isset($opts['prod']) && $opts['prod'] ? true : false;
        $this->info('Updating project dependencies');
        $this->composerUpdate(['prod' => $prod]);
        $this->info('Project dependencies updated');
    }

    //===================================================//
    // Composer Targets                                  //
    //===================================================//

    /**
     * Installs Composer dependencies
     *
     * @param array $opts The options
     *
     * @option $prod Optimize for production
     */
    public function composerInstall($opts = ['prod' => false])
    {
        $prod = isset($opts['prod']) && $opts['prod'] ? true : false;
        $paths = $this->getPaths();
        $this->stopOnFail(true);
        $this->yell('composer:install');
        $this->info('Installing Composer dependencies');
        $exec = $this->taskExec('composer')
            ->dir($paths['root'])
            ->arg('install')
            ->option('prefer-dist');
        if ($prod) {
            $exec->option('no-dev');
            $exec->option('optimize-autoloader');
        }
        $exec
            ->printed(true)
            ->run();
        $this->info('Composer dependencies installed');
    }

    /**
     * Updates Composer dependencies
     *
     * @param array $opts The options
     *
     * @option $prod Optimize for production
     */
    public function composerUpdate($opts = ['prod' => false])
    {
        $prod = isset($opts['prod']) && $opts['prod'] ? true : false;
        $paths = $this->getPaths();
        $this->stopOnFail(true);
        $this->yell('composer:update');
        $this->info('Updating Composer dependencies');
        $exec = $this->taskExec('composer')
            ->dir($paths['root'])
            ->arg('update')
            ->option('prefer-dist');
        if ($prod) {
            $exec->option('no-dev');
            $exec->option('optimize-autoloader');
        }
        $exec
            ->printed(true)
            ->run();
        $this->info('Composer dependencies updated');
    }

    /**
     * Updates composer.lock file hash
     */
    public function composerUpdateHash()
    {
        $paths = $this->getPaths();
        $this->stopOnFail(true);
        $this->yell('composer:update-hash');
        $this->info('Updating Composer lock file');
        $this->taskExec('composer')
            ->dir($paths['root'])
            ->arg('update')
            ->option('lock')
            ->printed(true)
            ->run();
        $this->info('Composer lock file updated');
    }

    //===================================================//
    // Directory Targets                                 //
    //===================================================//

    /**
     * Cleans artifact directories
     */
    public function dirClean()
    {
        $paths = $this->getPaths();
        $this->stopOnFail(true);
        $this->yell('dir:clean');
        $this->info('Cleaning artifact directories');
        $this->taskFileSystemStack()
            ->remove($paths['docapi'])
            ->remove($paths['coverage'])
            ->remove($paths['reports'])
            ->run();
        $this->info('Artifact directories cleaned');
    }

    /**
     * Prepares artifact directories
     */
    public function dirPrepare()
    {
        $paths = $this->getPaths();
        $this->stopOnFail(true);
        $this->dirClean();
        $this->yell('dir:prepare');
        $this->info('Preparing artifact directories');
        $this->taskFileSystemStack()
            ->mkdir($paths['docapi'])
            ->mkdir($paths['coverage'])
            ->mkdir($paths['reports'])
            ->run();
        $this->info('Artifact directories prepared');
    }

    //===================================================//
    // Documentation Targets                             //
    //===================================================//

    /**
     * Generates PHP API documentation
     *
     * @param array $opts The options
     *
     * @option $force Forces documentation rebuild from scratch
     */
    public function docsPhpApi($opts = ['force' => false])
    {
        $force = isset($opts['force']) && $opts['force'] ? true : false;
        $paths = $this->getPaths();
        $this->yell('docs:php-api');
        $this->info('Generating PHP API documentation');
        $exec = $this->taskExec('php')
            ->arg($paths['bin'].'/sami.php')
            ->arg('update');
        if ($force) {
            $exec->option('force');
        }
        $exec
            ->arg($paths['build'].'/sami.php')
            ->printed(true)
            ->run();
        $this->info('PHP API documentation generated');
    }

    //===================================================//
    // PHP Targets                                       //
    //===================================================//

    /**
     * Performs code style check on PHP source
     *
     * @param array $opts The options
     *
     * @option $report Generate an XML report for continuous integration
     */
    public function phpCodeStyle($opts = ['report' => false])
    {
        $report = isset($opts['report']) && $opts['report'] ? true : false;
        $paths = $this->getPaths();
        $this->stopOnFail(true);
        $this->yell('php:code-style');
        $this->info('Starting code style check for PHP source files');
        $exec = $this->taskExec('php')
            ->arg($paths['bin'].'/phpcs');
        if ($report) {
            $exec->option('report=checkstyle');
            $exec->option('report-file='.$paths['reports'].'/checkstyle.xml');
            $exec->option('warning-severity=0');
        }
        $exec->option('standard='.$paths['build'].'/phpcs.xml')
            ->arg($paths['src'])
            ->printed($report ? false : true)
            ->run();
        $this->info('PHP source files passed code style check');
    }

    /**
     * Performs syntax check on PHP source
     */
    public function phpLint()
    {
        $paths = $this->getPaths();
        $this->stopOnFail(true);
        $this->yell('php:lint');
        $this->info('Starting syntax check of PHP source files');
        $iterator = Finder::create()
            ->files()
            ->name('*.php')
            ->in($paths['src']);
        foreach ($iterator as $file) {
            $this->taskExec('php')
                ->arg('-l')
                ->arg($file->getRealPath())
                ->printed(false)
                ->run();
        }
        $this->info('PHP source files passed syntax check');
    }

    /**
     * Performs mess detection on PHP source
     *
     * @param array $opts The options
     *
     * @option $report Generate an XML report for continuous integration
     */
    public function phpMessDetect($opts = ['report' => false])
    {
        $report = isset($opts['report']) && $opts['report'] ? true : false;
        $paths = $this->getPaths();
        $this->stopOnFail(true);
        $this->yell('php:mess-detect');
        $this->info('Starting mess detection in PHP source files');
        $exec = $this->taskExec('php')
            ->arg($paths['bin'].'/phpmd')
            ->arg($paths['src'])
            ->arg($report ? 'xml' : 'text')
            ->arg($paths['build'].'/phpmd.xml');
        if ($report) {
            $exec->option('reportfile', $paths['reports'].'/pmd.xml');
        }
        $exec->printed($report ? false : true)
            ->run();
        $this->info('PHP source files passed mess detection');
    }

    /**
     * Runs PHPUnit tests
     */
    public function phpTest()
    {
        $paths = $this->getPaths();
        $this->stopOnFail(true);
        $phpunit = $paths['bin'].'/phpunit';
        $this->yell('php:test');
        $this->info('Running PHPUnit tests');
        $this->taskPHPUnit($phpunit)
            ->option('configuration', $paths['build'])
            ->option('testsuite', 'complete')
            ->printed(true)
            ->run();
        $this->info('Project passed PHPUnit tests');
    }

    //===================================================//
    // Helper Methods                                    //
    //===================================================//

    /**
     * Prints text with info color
     *
     * @param string $message The message
     */
    private function info($message)
    {
        $this->say(sprintf('<fg=blue>%s</fg=blue>', $message));
    }

    /**
     * Retrieves application paths
     *
     * @return array
     */
    private function getPaths()
    {
        if ($this->paths === null) {
            $this->paths = require __DIR__.'/paths.php';
        }
        return $this->paths;
    }
}
