<?php

namespace Pascal\ReadabilityBundle;

use \Symfony\Component\HttpKernel\Bundle\Bundle;
use \Symfony\Component\DependencyInjection\ContainerBuilder;

class PascalReadabilityBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
	}
}
