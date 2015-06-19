<?php

namespace JR\Phinx;

use Nette;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Phinx\Migration\Manager as PhinxManager;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Phinx\Config\ConfigInterface;

/**
 * Description of Manager.
 *
 * @author RebendaJiri <jiri.rebenda@htmldriven.com>
 */
class Manager extends Nette\Object
{
	/** @var PhinxManager */
	private $phinxManager;
	
	/** @var OutputInterface */
	private $output;
	
	/** @var ConfigInterface[] */
	private $migrationConfigurations = [];
	
	public function __construct(OutputInterface $output = NULL)
	{
		if ($output === NULL) {
			$output = $this->getDefaultOutput();
		}
		$this->setOutput($output);
	}
	
	/**
	 * @param OutputInterface $outputInterface
	 * @return self
	 */
	public function setOutput(OutputInterface $outputInterface)
	{
		$this->output = $outputInterface;
		return $this;
	}
	
	/**
	 * @param ConfigInterface $config
	 * @return self
	 * @throws InvalidArgumentException If configuration has been already added.
	 */
	public function addMigrationConfiguration(ConfigInterface $config)
	{
		if (in_array($config, $this->migrationConfigurations, TRUE)) {
			throw new InvalidArgumentException('Given migration configuration has been already added.');
		}
		$this->migrationConfigurations[] = $config;
		return $this;
	}
	
	/**
     * Migrate an environment to the specified version.
     *
     * @param string $environment Environment
     * @param int|NULL $version
     * @return void
     */
	public function migrate($environment, $version = NULL)
	{
		foreach ($this->migrationConfigurations as $migrationConfiguration) {
			$this->getPhinxManager()->setConfig($migrationConfiguration);
			$this->getPhinxManager()->migrate($environment, $version);
		}
	}
	
	/**
	 * @return PhinxManager
	 */
	protected function getPhinxManager()
	{
		if ($this->phinxManager !== NULL) {
			return $this->phinxManager;
		}
		return $this->phinxManager = new PhinxManager($this->getFirstMigrationConfiguration(), $this->output);
	}
	
	/**
	 * @return OutputInterface
	 */
	protected function getDefaultOutput()
	{
		return new NullOutput();
	}
	
	/**
	 * @return ConfigInterface
	 * @throws InvalidStateException If no migration configuration is set.
	 */
	private function getFirstMigrationConfiguration()
	{
		$copy = $this->migrationConfigurations;
		$migrationConfiguration = array_shift($copy);
		if ($migrationConfiguration === NULL) {
			throw new InvalidStateException('No migration configuration has been set.');
		}
		return $migrationConfiguration;
	}
}