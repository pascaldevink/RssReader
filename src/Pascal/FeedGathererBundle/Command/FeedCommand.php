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
		{
			$output->writeln("Updating feeds with the following input: '$lastUpdateTime'");
			$lastUpdateTime = new \DateTime($lastUpdateTime);
			$output->writeln("Input translates to the following time: " . print_r($lastUpdateTime, true));
		}
		else
		{
			$lastUpdateTime = new \DateTime('yesterday');
			$output->writeln("No input found, using: 'yesterday'");
			$output->writeln("Input translates to the following time: " . print_r($lastUpdateTime, true));
		}

		$feedHandler = $this->getFeedHandler();
		$numberOfEntries = $feedHandler->downloadFeeds($lastUpdateTime);

		$output->writeln("Added $numberOfEntries new entries");
	}

	/**
	 * @return \Pascal\FeedGathererBundle\Service\FeedDownloaderService
	 */
	protected function getFeedHandler()
	{
		$feedHandler = $this->getContainer()->get('feedDownloader');
		return $feedHandler;
	}
}
