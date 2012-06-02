<?php

namespace Pascal\FeedGathererBundle\Tests\Service;

use \Pascal\FeedGathererBundle\Entity\Feed;
use \Pascal\FeedGathererBundle\Entity\TwitterUser;
use Pascal\FeedGathererBundle\Service\TwitterFeedService;

class TwitterFeedServiceTest extends \PHPUnit_Framework_TestCase
{
	public function testIsUserOnBlacklist()
	{
		$service = new MockTwitterFeedService();
		$blacklist = array('pascaldevink', 'tweakers.net');

		$this->assertTrue($service->callIsUserOnBlacklist($blacklist, 'pascaldevink'));
		$this->assertFalse($service->callIsUserOnBlacklist($blacklist, 'iculture'));
	}

	public function testProcessItems()
	{
		$items = array();
		$item1 = array(
			"id" => 12738165059,
			'user' => array('name' => 'Pascal de Vink', 'screen_name' => 'pascaldevink'),
			'text' => 'This is a tweet.',
			'created_at' => 'Thu Oct 14 22:20:15 +0000 2010',
			'entities' => array('urls' => array(
				0 => array(
					'url' => 'http://t.co/0JG5Mcq',
					'display_url' => 'http://www.google.com/...',
					'expanded_url' => 'http://www.google.com/mail',
					'indices' => array(0, 25),
				)
			))
		);
		$item2 = array(
			'user' => array('name' => 'Jan Janssen', 'screen_name' => 'janjanssen'),
			'created_at' => 'Thu Oct 14 22:20:15 +0000 2010',
		);
		$items[] = $item1;
		$items[] = $item2;

		$twitterUser = new TwitterUser();
		$twitterUser->setBlacklistUsernames(array('janjanssen'));
		$feed = new Feed();
		$lastUpdateTime = new \DateTime();

		$logger = $this->getMockBuilder('\Symfony\Bridge\Monolog\Logger')
					->disableOriginalConstructor()
					->getMock();
		$logger
			->expects($this->once())
			->method('info')
			->will($this->returnValue(true));

		$service = new MockTwitterFeedService();
		$service->setLogger($logger);
		$feedEntries = $service->callProcessItems($items, $twitterUser, $feed, $lastUpdateTime);

		$this->assertEquals(1, count($feedEntries));
		$this->assertEquals('http://www.google.com/mail', $feedEntries[0]->getUrl());
		$this->assertEquals('Link shared by Pascal de Vink', $feedEntries[0]->getTitle());
		$this->assertEquals('Pascal de Vink', $feedEntries[0]->getAuthor());
		$this->assertEquals('This is a tweet.', $feedEntries[0]->getDescription());
		$this->assertEquals($feed, $feedEntries[0]->getFeed());
	}
}

class MockTwitterFeedService extends TwitterFeedService
{
	public function callIsUserOnBlacklist($blacklist, $username)
	{
		return $this->isUserOnBlacklist($blacklist, $username);
	}

	public function callProcessItems($items, TwitterUser $twitterUser, Feed $feed, \DateTime $lastUpdateTime)
	{
		return $this->processItems($items, $twitterUser, $feed, $lastUpdateTime);
	}

	public function setMockEntityManager($mockEntityManager)
	{
		$this->entityManager = $mockEntityManager;
	}
}