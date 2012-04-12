<?php

namespace Pascal\FeedGathererBundle\Service;

class FeedHandlerService
{

	/**
	 * @var FeedService[]
	 */
	private $feedHandlers;

	public function __construct()
	{
		$this->feedHandlers = array();
	}

	/**
	 * @param FeedService $feedHandler
	 */
	public function addFeedHandler(FeedService $feedHandler)
	{
		$this->feedHandlers[] = $feedHandler;
	}

	/**
	 * @return FeedService[]
	 */
	public function getFeedHandlers()
	{
		return $this->feedHandlers;
	}
}
