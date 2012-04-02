<?php

namespace Pascal\FeedGathererBundle\Tests\Service;

use \Pascal\FeedGathererBundle\Service\FeedDownloaderService;
use \Pascal\FeedGathererBundle\Service\FeedService;

class FeedDownloaderServiceTest extends \PHPUnit_Framework_TestCase
{

	public function testDownloadFeeds()
	{
		$mockFeedService = new MockFeedService();

		$feedDownloader = new FeedDownloaderService();
		$feedDownloader->addFeedService($mockFeedService);

		$feedDownloader->downloadFeeds(new \DateTime());

		$this->assertTrue($mockFeedService->isCalled);
	}
}

class MockFeedService implements FeedService
{
	public $isCalled = false;
	public $dateIsSet = false;

	public function downloadFeed(\DateTime $lastUpdateTime)
	{
		$this->isCalled = true;
	}
}