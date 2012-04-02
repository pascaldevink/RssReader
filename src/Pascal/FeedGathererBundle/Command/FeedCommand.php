<?php

namespace Pascal\FeedGathererBundle\Command;

use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class FeedCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName("feeds:gather")
			->setDescription("Gather new entries from all feeds")
			->setDefinition(array(
				new InputArgument(
					'lastUpdateTime',
					InputArgument::OPTIONAL,
					'The timestamp of the last update to get from'
				)
			));
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$lastUpdateTime = $input->getArgument('lastUpdateTime');
		if ($lastUpdateTime != null)
			$lastUpdateTime = new \DateTime($lastUpdateTime);
		else
			$lastUpdateTime = new \DateTime('yesterday');

		$feedHandler = $this->getContainer()->get('feedDownloader');
		$feedHandler->downloadFeeds($lastUpdateTime);
	}
}
