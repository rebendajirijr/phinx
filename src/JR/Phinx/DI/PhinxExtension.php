<?php

namespace JR\Phinx\DI;

use Nette\DI\CompilerExtension;

/**
 * Description of PhinxExtension.
 *
 * @author RebendaJiri <jiri.rebenda@htmldriven.com>
 */
class PhinxExtension extends CompilerExtension
{
	/** @var array */
	public $defaults = [
		'config' => [
			'files' => [],
		],
	];
	
	/*
	 * @inheritdoc
	 */
	public function loadConfiguration()
	{
		$config = $this->loadFromFile(__DIR__ . '/../../../../resources/config/phinx.neon');
		$this->compiler->parseServices($this->getContainerBuilder(), $config);
		
		$container = $this->getContainerBuilder();
		
		$manager = $container->addDefinition($this->prefix('manager'))
			->setClass('JR\Phinx\Manager', [NULL])
			->setInject(FALSE)
			->setAutowired(FALSE);
		
		foreach ($this->compiler->getExtensions('JR\Phinx\DI\IMigrationProvider') as $extension) {
			/* @var $extension IMigrationProvider */
			foreach ($extension->getMigrationConfigurations() as $migrationConfiguration) {
				$manager->addSetup('addMigrationConfiguration', [$migrationConfiguration]);
			}
		}
	}
}