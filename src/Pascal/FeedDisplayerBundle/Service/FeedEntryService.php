<?php

namespace Pascal\FeedDisplayerBundle\Service;

class FeedEntryService
{
	const DEFAULT_PAGE_SIZE = 25;

	const KEY_NUMBER_OF_PAGES = 'number_of_pages';
	const KEY_ENTRIES_FOR_PAGE = 'entries_for_page_';

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	private $cacheService;

	public function __construct(\Symfony\Bundle\DoctrineBundle\Registry $doctrine, CacheService $cacheService)
	{
		$this->entityManager = $doctrine->getEntityManager();
		$this->cacheService = $cacheService;
	}

	/**
	 * @return int
	 */
	public function getNumberOfFeedEntries()
	{
		$numberOfResults = $this->cacheService->get(self::KEY_NUMBER_OF_PAGES);
		if ($numberOfResults)
			return $numberOfResults;

		$query = $this->entityManager->createQuery(
			'SELECT COUNT(fe.id) FROM PascalFeedGathererBundle:FeedEntry fe ORDER BY fe.lastUpdateTime DESC'
		);

		$numberOfResults = $query->getSingleScalarResult();

		$this->cacheService->set(self::KEY_NUMBER_OF_PAGES, $numberOfResults);
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

		$entries = $this->cacheService->get(self::KEY_ENTRIES_FOR_PAGE . $page);
		if ($entries)
			return $entries;

		$query = $this->entityManager->createQuery(
			'SELECT fe FROM PascalFeedGathererBundle:FeedEntry fe ORDER BY fe.lastUpdateTime DESC'
		);

		$query->setFirstResult($page * $pageSize);
		$query->setMaxResults($pageSize);
		$entries = $query->getResult();

		$this->cacheService->set(self::KEY_ENTRIES_FOR_PAGE . $page, $entries);
		return $entries;
	}
}
