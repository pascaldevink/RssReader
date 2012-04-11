<?php

namespace Pascal\FeedGathererBundle\Tests\Service;

use Pascal\FeedGathererBundle\Service\TwitterFeedService;

class TwitterFeedServiceTest extends \PHPUnit_Framework_TestCase
{

	public function testDownloadFeed()
	{
		$this->markTestSkipped('Skipped untill further notice');

//		$mockDoctrine = new \Pascal\FeedGathererBundle\Tests\Mock\MockDoctrine();
//		$mockTwitter = new MockTwitter();
//
//		$twitterFeedService = new TwitterFeedService($mockDoctrine, $mockTwitter);
//
//		$feed = new \Pascal\FeedGathererBundle\Entity\Feed();
//		$feed->setDisabled(false);
//		$feed->setType('twitter');
//		$feed->setTypeId(1);
//		$feed->setLastUpdateTime(new \DateTime('yesterday'));
//
//		$twitterFeedService->downloadFeed($feed, new \DateTime());
	}
}

//class MockTwitter extends \tmhOAuth
//{
//
//}