<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dime\TimetrackerBundle\Composer;

use Symfony\Component\ClassLoader\ClassCollectionLoader;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class ScriptHandler
{
    public static function dimeRebuild($event)
    {
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];

        if (!is_dir($appDir)) {
            echo 'The symfony-app-dir ('.$appDir.') specified in composer.json was not found in '.getcwd().', can not clear the cache.'.PHP_EOL;

            return;
        }

        static::executeCommand($event, $appDir, 'dime:rebuild');
    }

    public static function doBuildBootstrap($appDir)
    {
        $file = $appDir.'/bootstrap.php.cache';
        if (file_exists($file)) {
            unlink($file);
        }

        ClassCollectionLoader::load(array(
            'Symfony\\Component\\DependencyInjection\\ContainerAwareInterface',
            // Cannot be included because annotations will parse the big compiled class file
            //'Symfony\\Component\\DependencyInjection\\ContainerAware',
            'Symfony\\Component\\DependencyInjection\\ContainerInterface',
            'Symfony\\Component\\DependencyInjection\\Container',
            'Symfony\\Component\\HttpKernel\\HttpKernelInterface',
            'Symfony\\Component\\HttpKernel\\KernelInterface',
            'Symfony\\Component\\HttpKernel\\Kernel',
            'Symfony\\Component\\ClassLoader\\ClassCollectionLoader',
            'Symfony\\Component\\ClassLoader\\UniversalClassLoader',
            'Symfony\\Component\\HttpKernel\\Bundle\\BundleInterface',
            'Symfony\\Component\\HttpKernel\\Bundle\\Bundle',
            'Symfony\\Component\\Config\\ConfigCache',
            // cannot be included as commands are discovered based on the path to this class via Reflection
            //'Symfony\\Bundle\\FrameworkBundle\\FrameworkBundle',
        ), dirname($file), basename($file, '.php.cache'), false, false, '.php.cache');

        file_put_contents($file, "<?php\n\nnamespace { require_once __DIR__.'/autoload.php'; }\n\n".substr(file_get_contents($file), 5));
    }

    protected static function executeCommand($event, $appDir, $cmd)
    {
        $phpFinder = new PhpExecutableFinder;
        $php = escapeshellarg($phpFinder->find());
        $console = escapeshellarg($appDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console.= ' --ansi';
        }

        $process = new Process($php.' '.$console.' '.$cmd);
        $process->run(function ($type, $buffer) { echo $buffer; });
    }

    protected static function executeBuildBootstrap($appDir)
    {
        $phpFinder = new PhpExecutableFinder;
        $php = escapeshellarg($phpFinder->find());
        $cmd = escapeshellarg(__DIR__.'/../Resources/bin/build_bootstrap.php');
        $appDir = escapeshellarg($appDir);

        $process = new Process($php.' '.$cmd.' '.$appDir);
        $process->run(function ($type, $buffer) { echo $buffer; });
    }

    protected static function getOptions($event)
    {
        $options = array_merge(array(
            'symfony-app-dir' => 'app',
            'symfony-web-dir' => 'web',
            'symfony-assets-install' => 'hard'
        ), $event->getComposer()->getPackage()->getExtra());

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];

        return $options;
    }
}
