<?php

namespace Pascal\FeedGathererBundle;

use \Symfony\Component\HttpKernel\Bundle\Bundle;
use \Symfony\Component\DependencyInjection\ContainerBuilder;

use \Pascal\FeedGathererBundle\DependencyInjection\FeedHandlerCompilerPass;

class PascalFeedGathererBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new FeedHandlerCompilerPass());
	}

}
