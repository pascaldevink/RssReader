<?php

namespace Pascal\FeedGathererBundle\Tests\Service;

use Pascal\FeedGathererBundle\Service\TwitterFeedService;

class TwitterFeedServiceTest extends \PHPUnit_Framework_TestCase
{

	public function testDownloadFeed()
	{
		$twitterFeedService = new TwitterFeedService();
		$twitterFeedService->downloadFeed(new \DateTime());
	}
}
