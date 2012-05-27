<?php

namespace Pascal\FeedDisplayerBundle\Service;

class FeedSettingsHandlerService
{

	private $feedSettingsHandlers = array();

	public function getFeedSettingsHandlers()
	{
		return $this->feedSettingsHandlers;
	}

	public function addFeedSettingsHandler($feedSettingsHandler)
	{
		$this->feedSettingsHandlers[] = $feedSettingsHandler;
	}
}
