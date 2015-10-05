<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dime\TimetrackerFrontendBundle\Composer;

use Symfony\Component\ClassLoader\ClassCollectionLoader;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\CommandEvent;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class ScriptHandler
{
    /**
     * Installs the assets under the web root directory.
     *
     * For better interoperability, assets are copied instead of symlinked by default.
     *
     * Even if symlinks work on Windows, this is only true on Windows Vista and later,
     * but then, only when running the console with admin rights or when disabling the
     * strict user permission checks (which can be done on Windows 7 but not on Windows
     * Vista).
     *
     * @param $event CommandEvent A instance
     */
    public static function publishAssets(CommandEvent $event)
    {
        $options = self::getOptions($event);
        static::executeCommand($event, $options['symfony-app-dir'], 'dime:publish-assets');
    }

    protected static function executeCommand(CommandEvent $event, $appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($appDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) { echo $buffer; });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd)));
        }
    }

    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(array(
                'symfony-app-dir' => 'app',
                'symfony-web-dir' => 'web',
                'symfony-assets-install' => 'hard'
            ), $event->getComposer()->getPackage()->getExtra());

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder;
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }
}
