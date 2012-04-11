<?php

namespace Pascal\FeedGathererBundle\Tests\Service;

require_once(dirname(__FILE__) . '/../../../../../vendor/simplepie/SimplePie.compiled.php');

use Pascal\FeedGathererBundle\Service\RssFeedService;

class RssFeedServiceTest extends \PHPUnit_Framework_TestCase
{

	public function testDownloadFeeds()
	{
		$this->markTestSkipped('Skipped untill further notice');

		$mockDoctrine = new \Pascal\FeedGathererBundle\Tests\Mock\MockDoctrine();
		$mockSimplePie = new MockSimplePie();
		$cacheDir = 'rss';

		$rssFeedService = new StubRssFeedService($mockDoctrine, $mockSimplePie, $cacheDir);

		$feed = new \Pascal\FeedGathererBundle\Entity\Feed();
		$feed->setDisabled(false);
		$feed->setType('twitter');
		$feed->setTypeId(1);
		$feed->setLastUpdateTime(new \DateTime('yesterday'));

		$rssFeedService->downloadFeed($feed, new \DateTime);

		$this->assertTrue(true);
	}
}

class StubRssFeedService extends RssFeedService
{
	protected function checkCacheDir($cacheDir)
	{
		// Do nothing
	}

	protected function getFeeds()
	{
		return array();
	}
}

class MockSimplePie extends \SimplePie
{
}
