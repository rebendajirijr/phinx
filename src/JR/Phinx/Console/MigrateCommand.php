<?php

namespace JR\Phinx\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JR\Phinx\Manager;

/**
 * Description of MigrateCommand.
 *
 * @author RebendaJiri <jiri.rebenda@htmldriven.com>
 */
class MigrateCommand extends Command
{
	/*
	 * @inheritdoc
	 */
	protected function configure()
	{
		parent::configure();
		
		$this->setName('phinx:migrate')
			->setDescription('Processes all registered migration configurations.')
			->addOption('--environment', '-e', InputOption::VALUE_REQUIRED, 'The target environment')
			->addOption('--version', '-v', InputOption::VALUE_OPTIONAL, 'The version number to migrate to');
	}
	
	/*
	 * @inheritdoc
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/* @var $manager Manager */
		$manager = $this->getHelper('container')->getByType('JR\Phinx\Manager');
		
		$environment = $input->getOption('environment');
		$version = $input->getOption('version');
		
		$manager->migrate($environment, $version);
		return 0;
	}
}