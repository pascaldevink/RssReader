<?php

namespace Pascal\FeedGathererBundle\Tests\Mock;

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