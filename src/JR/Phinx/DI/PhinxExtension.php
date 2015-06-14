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
	/*
	 * @inheritdoc
	 */
	public function loadConfiguration()
	{
		$config = $this->loadFromFile(__DIR__ . '/../../../../resources/config/phinx.neon');
		$this->compiler->parseServices($this->getContainerBuilder(), $config);
	}
}