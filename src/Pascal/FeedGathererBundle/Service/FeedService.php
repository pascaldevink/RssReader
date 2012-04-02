<?php

namespace Pascal\FeedGathererBundle\Service;

interface FeedService
{

	public function downloadFeed(\DateTime $lastUpdateTime);
}
