<?php

namespace Pascal\FeedGathererBundle\Tests\Service;

use \Pascal\FeedGathererBundle\Service\FeedDownloaderService;
use \Pascal\FeedGathererBundle\Service\FeedService;

class FeedDownloaderServiceTest extends \PHPUnit_Framework_TestCase
{

	public function testDownloadFeeds()
	{
		$feedHandlerService = new \Pascal\FeedGathererBundle\Service\FeedHandlerService();
		$mockFeedHandler = new MockFeedHandler('mock');
		$feedHandlerService->addFeedHandler($mockFeedHandler);
		$secondMockFeedHandler = new MockFeedHandler('mock2');
		$feedHandlerService->addFeedHandler($secondMockFeedHandler);

		$feedDownloader = new MockFeedDownloaderService();
		$feedDownloader->setFeedHandlerService($feedHandlerService);
		$feedDownloader->downloadFeeds(new \DateTime());

		$this->assertTrue($mockFeedHandler->getIsCalled());
		$this->assertEquals(1, $mockFeedHandler->getCallTimes());

		$this->assertFalse($secondMockFeedHandler->getIsCalled());
		$this->assertEquals(0, $secondMockFeedHandler->getCallTimes());
	}
}

class MockFeedDownloaderService extends FeedDownloaderService
{
	protected function getFeeds(\DateTime $lastUpdateTime)
	{
		$feeds = array();

		$feed1 = new \Pascal\FeedGathererBundle\Entity\Feed();
		$feed1->setType('mock');
		$feed1->setTypeId(1);
		$feed1->setDisabled(false);
		$feeds[] = $feed1;

		return $feeds;
	}

	protected function save()
	{
		return 1;
	}
}

class MockFeedHandler implements FeedService
{
	private $isCalled = false;
	private $callTimes = 0;
	private $serviceType = 'mock';

	public function __construct($serviceType)
	{
		$this->serviceType = $serviceType;
	}

	public function getServiceType()
	{
		return $this->serviceType;
	}

	public function downloadFeed(\Pascal\FeedGathererBundle\Entity\Feed $feed, \DateTime $lastUpdateTime)
	{
		$this->isCalled = true;
		$this->callTimes++;
	}

	public function getIsCalled()
	{
		return $this->isCalled;
	}

	public function getCallTimes()
	{
		return $this->callTimes;
	}
}
