<?php
namespace Dime\TimetrackerFrontendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishAssetsCommand extends ContainerAwareCommand
{
    public function __construct($name = null)
    {
        parent::__construct($name);

    }

    protected function configure()
    {
        $this
            ->setName('dime:publish-assets')
            ->setAliases(array('dpa'))
            ->setDescription('Publish assets and clear cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $returnCode = $this->runExternalCommand('twitter-bootstrap:clear', $output);
        $returnCode = $this->runExternalCommand('twitter-bootstrap:compile', $output);

        switch ($input->getOption("env")) {
            case 'prod':
                $returnCode = $this->runExternalCommand('assetic:dump', $output);
                break;
            default:
                $returnCode = $this->runExternalCommand(
                    'assets:install',
                    $output,
                    array('--symlink' => true, '--relative' => true, 'target' => 'web/')
                );
        }

        $returnCode = $this->runExternalCommand('cache:clear', $output);
    }

    protected function runExternalCommand($name, $output, array $input = array())
    {
        $command = $this->getApplication()->find($name);

        if ($command) {
            $args = array_merge(array('command' => $name), $input);
            $input = new ArrayInput($args);

            return $command->run($input, $output);
        } else {
            return false;
        }
    }
}
