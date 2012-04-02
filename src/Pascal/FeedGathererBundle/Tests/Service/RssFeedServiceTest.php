<?php

namespace Pascal\FeedGathererBundle\Tests\Service;

require_once(dirname(__FILE__) . '/../../../../../vendor/simplepie/SimplePie.compiled.php');

use Pascal\FeedGathererBundle\Service\RssFeedService;

class RssFeedServiceTest extends \PHPUnit_Framework_TestCase
{

	public function testDownloadFeeds()
	{
		$mockDoctrine = new MockDoctrine();
		$mockSimplePie = new MockSimplePie();
		$cacheDir = 'rss';

		$rssFeedService = new StubRssFeedService($mockDoctrine, $mockSimplePie, $cacheDir);
		$rssFeedService->downloadFeed(new \DateTime);

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

class MockDoctrine extends \Symfony\Bundle\DoctrineBundle\Registry
{
	public function __construct()
	{
	}

	public function getEntityManager($name = null)
	{
		return new MockEntityManager();
	}
}

class MockEntityManager extends \Doctrine\ORM\EntityManager
{
	public function __construct()
	{
	}

	public function flush()
	{
		// Do nothing
	}
}

class MockSimplePie extends \SimplePie
{
}
