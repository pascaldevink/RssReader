<?php

namespace Pascal\FeedGathererBundle\Service;

interface FeedService
{
	public function getServiceType();

	public function downloadFeed(\Pascal\FeedGathererBundle\Entity\Feed $feed, \DateTime $lastUpdateTime);
}
