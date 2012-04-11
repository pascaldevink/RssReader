<?php

namespace Pascal\FeedGathererBundle\Tests\Service;

use \Pascal\FeedGathererBundle\Service\FeedDownloaderService;
use \Pascal\FeedGathererBundle\Service\FeedService;

class FeedDownloaderServiceTest extends \PHPUnit_Framework_TestCase
{

	public function testDownloadFeeds()
	{
		$this->markTestSkipped('Skipped untill further notice');
		
		$mockFeedService = new MockFeedService();

		$feedDownloader = new FeedDownloaderService(new \Pascal\FeedGathererBundle\Tests\Mock\MockDoctrine());
		$feedDownloader->addFeedService($mockFeedService);

		$feedDownloader->downloadFeeds(new \DateTime());

		$this->assertTrue($mockFeedService->isCalled);
	}
}

class MockFeedService implements FeedService
{
	public $isCalled = false;
	public $dateIsSet = false;

	public function downloadFeed(\Pascal\FeedGathererBundle\Entity\Feed $feed,  \DateTime $lastUpdateTime)
	{
		$this->isCalled = true;
	}

	public function getServiceType()
	{
		return 'mock';
	}
}