<?php

namespace Pascal\FeedGathererBundle\Tests\Mock;

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
