<?php

namespace Pascal\FeedGathererBundle\Service;

use \Pascal\FeedGathererBundle\Entity\Feed;

class RssFeedService implements FeedService
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 * @var \SimplePie
	 */
	protected $rssReader;

	public function __construct(\Symfony\Bundle\DoctrineBundle\Registry $doctrine, \SimplePie $rssReader, $cacheDir)
	{
		$this->entityManager = $doctrine->getEntityManager();
		$this->rssReader = $rssReader;

		$this->checkCacheDir($cacheDir);

		$this->rssReader->set_cache_location($cacheDir);
	}

	/**
	 * @param \DateTime $lastUpdateTime
	 */
	public function downloadFeed(\DateTime $lastUpdateTime)
	{
		$feeds = $this->getFeeds();
		foreach ($feeds as $feed)
		{
//			var_dump("Processing '".$feed->getTitle()."' feed");
			$items = $this->getEntriesFromFeed($feed);
			$this->processItems($items, $feed, $lastUpdateTime);
		}

		$this->entityManager->flush();
	}

	/**
	 * @param \Pascal\FeedGathererBundle\Entity\Feed $feed
	 * @return \SimplePie_Item[]
	 */
	protected function getEntriesFromFeed(Feed $feed)
	{
		$this->rssReader->set_feed_url($feed->getUrl());
		$this->rssReader->init();

		$items = $this->rssReader->get_items();
		return $items;
	}

	/**
	 * @param string $cacheDir
	 */
	protected function checkCacheDir($cacheDir)
	{
		if (!is_dir($cacheDir . DIRECTORY_SEPARATOR . 'rss'))
			mkdir($cacheDir . DIRECTORY_SEPARATOR . 'rss');
	}

	/**
	 * @return \Pascal\FeedGathererBundle\Entity\Feed[]
	 */
	protected function getFeeds()
	{
		$query = $this->entityManager
			->createQuery("SELECT f FROM PascalFeedGathererBundle:Feed f WHERE f.disabled = :disabled")
			->setParameter('disabled', 0);

		$feeds = $query->getResult();
		return $feeds;
	}

	/**
	 * @param \SimplePie_Item[] $items
	 * @param \Pascal\FeedGathererBundle\Entity\Feed $feed
	 * @param \DateTime $lastUpdateTime
	 */
	protected function processItems($items, Feed $feed, \DateTime $lastUpdateTime)
	{
		foreach ($items as $item)
		{
			$dateTime = new \DateTime($item->get_date());

			if ($dateTime < $lastUpdateTime)
				continue;

			$feedEntry = new \Pascal\FeedGathererBundle\Entity\FeedEntry();
			$feedEntry->setTitle($item->get_title());
			$feedEntry->setDescription($item->get_description());
			$feedEntry->setAuthor($this->getAuthor($item, $feed));
			$feedEntry->setUrl($item->get_link());
			$feedEntry->setLastUpdateTime($dateTime);
			$feedEntry->setFeed($feed);

			$this->entityManager->persist($feedEntry);
		}
	}

	/**
	 * @param \SimplePie_Item $item
	 * @param \Pascal\FeedGathererBundle\Entity\Feed $feed
	 */
	protected function getAuthor(\SimplePie_Item $item, Feed $feed)
	{
		$authorName = '';
		if ($item->get_author() == null)
		{
			$authorName = $feed->getTitle();
		}
		else
		{
			$author = $item->get_author();
			if ($author->get_name() != null)
			{
				$authorName = $author->get_name();
			}
			else if ($author->get_email() != null)
			{
				$authorName = $author->get_email();
			}
			else
			{
				$authorName = $feed->getTitle();
			}
		}

		return $authorName;
	}
}
