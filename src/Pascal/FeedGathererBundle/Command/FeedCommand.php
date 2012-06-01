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

		$feedHandler = $this->getFeedDownloader();
		$numberOfEntries = $feedHandler->downloadFeeds($lastUpdateTime);

		$output->writeln("Added $numberOfEntries new entries");

		if ($numberOfEntries > 0)
		{
			try {
				$numberOfHandlers = count($this->getFeedHandlerService()->getFeedHandlers());
				$this->mailResults($numberOfEntries, $numberOfHandlers);
			}
			catch(\Swift_TransportException $e)
			{
				$this->getContainer()->get('logger')->err('Unable to send emails: ' . $e->getMessage());
			}
		}
	}

	protected function mailResults($numberOfEntries, $numberOfHandlers)
	{
		$message = \Swift_Message::newInstance()
			->setSubject('RssReader import log')
			->setFrom('noreply@inpiggy.nl')
			->setTo('pascal.de.vink@gmail.com')
			->setBody(
				$this->getContainer()->get('templating')->render(
					'PascalFeedGathererBundle:Feed:mail.txt.twig',
					array('numberOfEntries' => $numberOfEntries, 'numberOfHandlers' => $numberOfHandlers)
				)
			);

		$this->getContainer()->get('mailer')->send($message);
	}

	/**
	 * @return \Pascal\FeedGathererBundle\Service\FeedDownloaderService
	 */
	protected function getFeedDownloader()
	{
		$feedDownloader = $this->getContainer()->get('feedDownloader');
		return $feedDownloader;
	}

	/**
	 * @return \Pascal\FeedGathererBundle\Service\FeedHandlerService
	 */
	public function getFeedHandlerService()
	{
		$feedHandler = $this->getContainer()->get('feedHandler');
		return $feedHandler;
	}
}
