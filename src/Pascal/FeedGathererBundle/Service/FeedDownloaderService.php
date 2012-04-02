<?php

namespace Pascal\FeedGathererBundle\Service;

/**
 * @author Pascal de Vink
 */
class FeedDownloaderService
{
	/**
	 * @var FeedService[]
	 */
	private $feedServices;

	public function __construct()
	{
		$this->feedServices = array();
	}

	/**
	 * @param \DateTime $lastUpdateTime
	 */
	public function downloadFeeds(\DateTime $lastUpdateTime)
	{
		foreach($this->feedServices as $feedHandler)
		{
			$feedHandler->downloadFeed($lastUpdateTime);
		}
	}

	/**
	 * @param $feedService
	 */
	public function addFeedService($feedService)
	{
		$this->feedServices[] = $feedService;
	}
}
