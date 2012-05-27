<?php

namespace Pascal\FeedDisplayerBundle;

use \Pascal\FeedDisplayerBundle\DependencyInjection\FeedSettingsHandlerCompilerPass;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PascalFeedDisplayerBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new FeedSettingsHandlerCompilerPass());
	}
}
