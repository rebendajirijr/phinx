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
	/** @var string */
	const MANAGER_SERVICE_BASENAME = 'manager';
	
	/** @var array */
	public $defaults = [];
	
	/*
	 * @inheritdoc
	 */
	public function loadConfiguration()
	{
		$config = $this->loadFromFile(__DIR__ . '/../../../../resources/config/phinx.neon');
		$this->compiler->parseServices($this->getContainerBuilder(), $config);
		
		$container = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);
		
		$container->addDefinition($this->prefix(static::MANAGER_SERVICE_BASENAME))
			->setClass('JR\Phinx\Manager', [NULL])
			->setInject(FALSE);		
	}
	
	/*
	 * @inheritdoc
	 */
	public function beforeCompile()
	{
		$container = $this->getContainerBuilder();
		$manager = $container->getDefinition($this->prefix(static::MANAGER_SERVICE_BASENAME));
		
		foreach ($this->compiler->getExtensions('JR\Phinx\DI\IMigrationProvider') as $extension) {
			/* @var $extension IMigrationProvider */
			foreach ($extension->getMigrationConfigurations() as $migrationConfiguration) {
				$manager->addSetup('addMigrationConfiguration', [$migrationConfiguration]);
			}
		}
	}
}