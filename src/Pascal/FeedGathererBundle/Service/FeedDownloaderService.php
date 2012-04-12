<?php

namespace Pascal\FeedGathererBundle\Service;

/**
 * @author Pascal de Vink
 */
class FeedDownloaderService
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 * @var FeedHandlerService
	 */
	private $feedHandlerService;

	public function __construct(\Symfony\Bundle\DoctrineBundle\Registry $doctrine, FeedHandlerService $feedHandlerService)
	{
		$this->entityManager = $doctrine->getEntityManager();
		$this->feedHandlerService = $feedHandlerService;
	}

	/**
	 * @param \DateTime $lastUpdateTime
	 */
	public function downloadFeeds(\DateTime $lastUpdateTime)
	{
		$feeds = $this->getFeeds($lastUpdateTime);
		$feedServices = $this->feedHandlerService->getFeedHandlers();

		foreach($feeds as $feed)
		{
			foreach($feedServices as $feedHandler)
			{
				if ($feedHandler->getServiceType() == $feed->getType())
				{
					$feedHandler->downloadFeed($feed, $lastUpdateTime);
					break;
				}
			}

			$feed->setLastUpdateTime(new \DateTime('now'));
		}

		$this->entityManager->flush();
	}

	/**
	 * @param \DateTime $lastUpdateTime
	 * @return \Pascal\FeedGathererBundle\Entity\Feed[]
	 */
	protected function getFeeds(\DateTime $lastUpdateTime)
	{
		$dql = "
			SELECT f
			FROM PascalFeedGathererBundle:Feed f
			WHERE f.disabled = :disabled
			AND f.lastUpdateTime < :lastUpdateTime";

		$query = $this->entityManager
			->createQuery($dql)
			->setParameter('disabled', 0)
			->setParameter('lastUpdateTime', $lastUpdateTime);

		$feeds = $query->getResult();
		return $feeds;
	}
}
