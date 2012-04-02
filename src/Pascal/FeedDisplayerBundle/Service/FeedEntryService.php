<?php

namespace Pascal\FeedDisplayerBundle\Service;

class FeedEntryService
{
	const DEFAULT_PAGE_SIZE = 25;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $entityManager;

	public function __construct(\Symfony\Bundle\DoctrineBundle\Registry $doctrine)
	{
		$this->entityManager = $doctrine->getEntityManager();
	}

	/**
	 * @return int
	 */
	public function getNumberOfFeedEntries()
	{
		$query = $this->entityManager->createQuery(
			'SELECT COUNT(fe.id) FROM PascalFeedGathererBundle:FeedEntry fe ORDER BY fe.lastUpdateTime DESC'
		);

		$numberOfResults = $query->getSingleScalarResult();
		return $numberOfResults;
	}

	/**
	 * @param int $page
	 * @param int $pageSize
	 * @return \Pascal\FeedGathererBundle\Entity\FeedEntry[]
	 */
	public function getAllFeedEntries($page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
	{
		$page = --$page;

		$query = $this->entityManager->createQuery(
			'SELECT fe FROM PascalFeedGathererBundle:FeedEntry fe ORDER BY fe.lastUpdateTime DESC'
		);

		$query->setFirstResult($page * $pageSize);
		$query->setMaxResults($pageSize);
		$entries = $query->getResult();
		return $entries;
	}
}
