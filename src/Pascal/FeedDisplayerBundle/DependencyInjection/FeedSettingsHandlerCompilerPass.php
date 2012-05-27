<?php

namespace Pascal\FeedDisplayerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class FeedSettingsHandlerCompilerPass implements CompilerPassInterface
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
		if (false === $container->hasDefinition('feedSettingsHandler')) {
			return;
		}

		$definition = $container->getDefinition('feedSettingsHandler');

		foreach ($container->findTaggedServiceIds('feed.settings.handler') as $id => $attributes) {
			$definition->addMethodCall('addFeedSettingsHandler', array(new Reference($id)));
		}
	}
}
