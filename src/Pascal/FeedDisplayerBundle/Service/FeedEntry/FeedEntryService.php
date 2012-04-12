<?php

namespace Pascal\FeedDisplayerBundle\Service\FeedEntry;

use \Pascal\FeedDisplayerBundle\Service\FeedEntry\FeedEntryFilterSettings;
use \Pascal\FeedDisplayerBundle\Entity\ItemResult;

class FeedEntryService
{
	const KEY_NUMBER_OF_PAGES = 'number_of_pages';
	const KEY_ENTRIES_FOR_PAGE = 'entries_for_page_';

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	private $cacheService;

	public function __construct(\Symfony\Bundle\DoctrineBundle\Registry $doctrine, \Pascal\FeedDisplayerBundle\Service\CacheService $cacheService)
	{
		$this->entityManager = $doctrine->getEntityManager();
		$this->cacheService = $cacheService;
	}

	/**
	 * Returns feed entries wrapped in an ItemResult object.
	 * The list of feed entries is based on the given filter settings.
	 *
	 * @param \Pascal\FeedDisplayerBundle\Service\FeedEntry\FeedEntryFilterSettings $filterSettings
	 * @return \Pascal\FeedDisplayerBundle\Entity\ItemResult
	 */
	public function getFeedEntries(FeedEntryFilterSettings $filterSettings)
	{
		$itemResult = new ItemResult();

		$items = $this->getAllFeedEntries($filterSettings->getPage(), $filterSettings->getPageSize());
		$totalCount = $this->getNumberOfFeedEntries();
		$filteredCount = count($items);

		$itemResult->setItemList($items);
		$itemResult->setTotalCount($totalCount);
		$itemResult->setFilteredCount($filteredCount);

		return $itemResult;
	}

	/******************************************************************************
	 * Database accessing methods ahead. They should all be protected or public.
	 ******************************************************************************/

	/**
	 * @param int $page
	 * @param int $pageSize
	 * @return \Pascal\FeedGathererBundle\Entity\FeedEntry[]
	 */
	protected function getAllFeedEntries($page, $pageSize)
	{
		$page = --$page;

		$entries = $this->cacheService->get(self::KEY_ENTRIES_FOR_PAGE . $page);
		if ($entries)
		{
			return $entries;
		}

		$query = $this->entityManager->createQuery(
			'SELECT fe FROM PascalFeedGathererBundle:FeedEntry fe ORDER BY fe.lastUpdateTime DESC'
		);

		$query->setFirstResult($page * $pageSize);
		$query->setMaxResults($pageSize);
		$entries = $query->getResult();

		$this->cacheService->set(self::KEY_ENTRIES_FOR_PAGE . $page, $entries);
		return $entries;
	}

	/**
	 * @return int
	 */
	protected function getNumberOfFeedEntries()
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
}
