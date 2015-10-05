<?php
namespace Dime\TimetrackerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDbCommand extends ContainerAwareCommand
{
    public function __construct($name = null)
    {
        parent::__construct($name);

    }

    protected function configure()
    {
        $this
            ->setName('dime:create-db')
            ->setDescription('Drop and create database schema and load fixtures in dev/test environment')
            ->addOption('drop', null, InputOption::VALUE_NONE, 'Drop and create database');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption("drop")) {
            $returnCode = $this->runExternalCommand('doctrine:database:drop', $output, array('--force' => true));
            $returnCode = $this->runExternalCommand('doctrine:database:create', $output);
        } else {
            $returnCode = $this->runExternalCommand('doctrine:schema:drop', $output, array('--force' => true));
            $returnCode = $this->runExternalCommand('doctrine:schema:create', $output);

            if ($input->getOption("env") === 'dev' || $input->getOption("env") === 'test') {
                $returnCode = $this->runExternalCommand('doctrine:fixtures:load', $output);
            }
        }
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
