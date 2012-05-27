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

	/**
	 * @param \Symfony\Bundle\DoctrineBundle\Registry $doctrine
	 */
	public function setEntityManager(\Symfony\Bundle\DoctrineBundle\Registry $doctrine)
	{
		$this->entityManager = $doctrine->getEntityManager();
	}

	/**
	 * @param \Pascal\FeedGathererBundle\Service\FeedHandlerService $feedHandlerService
	 */
	public function setFeedHandlerService($feedHandlerService)
	{
		$this->feedHandlerService = $feedHandlerService;
	}

	/**
	 * @param \DateTime $lastUpdateTime
	 */
	public function downloadFeeds(\DateTime $lastUpdateTime)
	{
		$feeds = $this->getFeeds($lastUpdateTime);
		$feedServices = $this->feedHandlerService->getFeedHandlers();

		$numberOfEntries = 0;

		foreach($feeds as $feed)
		{
			foreach($feedServices as $feedHandler)
			{
				if ($feedHandler->getServiceType() == $feed->getType())
				{
					$numberOfEntries += $feedHandler->downloadFeed($feed, $lastUpdateTime);
					break;
				}
			}

			$feed->setLastUpdateTime(new \DateTime('now'));
		}

		$this->save();
		return $numberOfEntries;
	}

	/**
	 * Flush the work in the entity manager
	 */
	protected function save()
	{
		$this->entityManager->getUnitOfWork()->size();
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
