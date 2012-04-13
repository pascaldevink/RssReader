<?php

namespace Pascal\FeedGathererBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class FeedHandlerCompilerPass implements CompilerPassInterface
{

	/**
	 * You can modify the container here before it is dumped to PHP code.
	 *
	 * @param ContainerBuilder $container
	 *
	 * @return void
	 *
	 * @api
	 */
	function process(ContainerBuilder $container)
	{
		if (false === $container->hasDefinition('feedHandler')) {
			return;
		}

		$definition = $container->getDefinition('feedHandler');

		foreach ($container->findTaggedServiceIds('feed.handler') as $id => $attributes) {
			$definition->addMethodCall('addFeedHandler', array(new Reference($id)));
		}
	}
}
