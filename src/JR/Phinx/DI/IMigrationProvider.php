<?php

namespace JR\Phinx\DI;

use Phinx\Config\ConfigInterface;

/**
 * Description of IMigrationProvider.
 * 
 * @author RebendaJiri <jiri.rebenda@htmldriven.com>
 */
interface IMigrationProvider
{
	/**
	 * Returns array of Phinx migration configurations.
	 * 
	 * @return ConfigInterface[]
	 */
	function getMigrationConfigurations();
}