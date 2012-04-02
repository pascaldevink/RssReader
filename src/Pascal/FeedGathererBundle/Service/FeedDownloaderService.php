<?php

namespace Pascal\FeedGathererBundle\Service;

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

	public function downloadFeeds($lastUpdateTime)
	{
		foreach($this->feedServices as $feedHandler)
		{
			$feedHandler->downloadFeed($lastUpdateTime);
		}
	}

	public function addFeedService($feedService)
	{
		$this->feedServices[] = $feedService;
	}
}
