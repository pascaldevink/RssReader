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

//	private $cacheService;

	public function __construct(\Symfony\Bundle\DoctrineBundle\Registry $doctrine)
//								\Pascal\FeedDisplayerBundle\Service\CacheService $cacheService)
	{
		$this->entityManager = $doctrine->getEntityManager();
//		$this->cacheService = $cacheService;
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

		$items = $this->getAllFeedEntries($filterSettings);
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
	 * Retrieve a FeedEntry by its id.
	 *
	 * @param int $feedEntryId
	 * @return \Pascal\FeedGathererBundle\Entity\FeedEntry
	 */
	public function getFeedEntryById($feedEntryId)
	{
		$id = (int)$feedEntryId;
		$feedEntry = $this->entityManager->getRepository("PascalFeedGathererBundle:FeedEntry")->find($id);
		return $feedEntry;
	}

	/**
	 * @param FeedEntryFilterSettings $filterSettings
	 * @return \Pascal\FeedGathererBundle\Entity\FeedEntry[]
	 */
	protected function getAllFeedEntries(FeedEntryFilterSettings $filterSettings)
	{
		$page = $filterSettings->getPage();
		$pageSize = $filterSettings->getPageSize();

		$page = --$page;

		// FIXME: Fix caching
//		$entries = $this->cacheService->get(self::KEY_ENTRIES_FOR_PAGE . $page);
//		if ($entries)
//		{
//			return $entries;
//		}

		$whereClause = 'WHERE';
		if ($filterSettings->getSource()->wasSet())
		{
			$whereClause .= ' f.type = :feedType';
		}

		if ($filterSettings->getTagList()->wasSet())
		{
			if ($whereClause !== 'WHERE')
			{
				$whereClause .= ' AND';
			}
			$whereClause .= ' t.name IN (:tagNames)';
		}

		if ($filterSettings->getLastUpdateTime()->wasSet())
		{
			if ($whereClause !== 'WHERE')
			{
				$whereClause .= ' AND';
			}
			$whereClause .= ' fe.lastUpdateTime > :lastUpdateTime';
		}

		$dql = '
			SELECT fe, f
			FROM PascalFeedGathererBundle:FeedEntry fe
			JOIN fe.feed f
			LEFT JOIN fe.tags t
		';

		if ($whereClause !== 'WHERE')
			$dql .= $whereClause;

		$dql .= ' ORDER BY fe.'. $filterSettings->getOrderField() .' ' . $filterSettings->getOrderType();

		$query = $this->entityManager->createQuery($dql);

		if ($filterSettings->getSource()->wasSet())
			$query->setParameter('feedType', $filterSettings->getSource()->getValue());

		if ($filterSettings->getTagList()->wasSet())
			$query->setParameter('tagNames', implode(',', $filterSettings->getTagList()->getValue()));

		if ($filterSettings->getLastUpdateTime()->wasSet())
			$query->setParameter('lastUpdateTime', $filterSettings->getLastUpdateTime()->getValue());

		if ($filterSettings->getPaging())
		{
			$query->setFirstResult($page * $pageSize);
			$query->setMaxResults($pageSize);
		}

		$query->useResultCache(true);
		$entries = $query->getResult();

		// FIXME: Fix caching
//		$this->cacheService->set(self::KEY_ENTRIES_FOR_PAGE . $page, $entries);

		return $entries;
	}

	/**
	 * @return int
	 */
	protected function getNumberOfFeedEntries()
	{
		// FIXME: Fix caching
//		$numberOfResults = $this->cacheService->get(self::KEY_NUMBER_OF_PAGES);
//		if ($numberOfResults)
//			return $numberOfResults;

		$query = $this->entityManager->createQuery(
			'SELECT COUNT(fe.id) FROM PascalFeedGathererBundle:FeedEntry fe ORDER BY fe.lastUpdateTime DESC'
		);

		$numberOfResults = $query->getSingleScalarResult();

		// FIXME: Fix caching
//		$this->cacheService->set(self::KEY_NUMBER_OF_PAGES, $numberOfResults);

		return $numberOfResults;
	}

	/**
	 * Delete a Feed Entry object
	 *
	 * @param int $feedEntryId
	 */
	public function deleteFeedEntry($feedEntryId)
	{
		$query = $this->entityManager->createQuery(
			'DELETE PascalFeedGathererBundle:FeedEntry fe WHERE fe.id = :feedEntryId'
		);
		$query->setParameter('feedEntryId', $feedEntryId);
		$query->execute();
	}
}
