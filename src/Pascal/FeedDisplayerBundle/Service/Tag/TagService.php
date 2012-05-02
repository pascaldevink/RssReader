<?php

namespace Pascal\FeedDisplayerBundle\Service\Tag;

use \Pascal\FeedDisplayerBundle\Entity\Tag;

class TagService
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $entityManager;

	public function __construct(\Symfony\Bundle\DoctrineBundle\Registry $doctrine)
	{
		$this->entityManager = $doctrine->getEntityManager();
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Entity\ItemResult
	 */
	public function getTags()
	{
		$itemResult = new \Pascal\FeedDisplayerBundle\Entity\ItemResult();

		$tags = $this->getTagsFromDatabase();
		$itemResult->setItemList($tags);
		$itemResult->setTotalCount(count($tags));
		$itemResult->setFilteredCount(count($tags));

		return $itemResult;
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Entity\ItemResult
	 */
	public function getTagsWithFeedEntries()
	{
		$itemResult = new \Pascal\FeedDisplayerBundle\Entity\ItemResult();

		$tags = $this->getTagsWithFeedEntriesFromDatabase();
		$itemResult->setItemList($tags);
		$itemResult->setTotalCount(count($tags));
		$itemResult->setFilteredCount(count($tags));

		return $itemResult;
	}

	/**
	 * Get a tab by its id or return null.
	 *
	 * @param string $name
	 * @return \Pascal\FeedDisplayerBundle\Entity\Tag|null
	 */
	public function getTagById($id)
	{
		$tag = $this->entityManager->getRepository("PascalFeedDisplayerBundle:Tag")->find($id);
		return $tag;
	}

	/**
	 * Get a tab by its name or return null.
	 *
	 * @param string $name
	 * @return \Pascal\FeedDisplayerBundle\Entity\Tag|null
	 */
	public function getTagByName($name)
	{
		$tag = $this->entityManager->getRepository("PascalFeedDisplayerBundle:Tag")->findOneByName($name);
		return $tag;
	}

	/**
	 * Adds a new tag to the database.
	 *
	 * @param string $name
	 * @return \Pascal\FeedDisplayerBundle\Entity\Tag
	 */
	public function addNewTag($name)
	{
		$tag = new Tag();
		$tag->setName($name);

		$this->entityManager->persist($tag);
		$this->entityManager->flush();

		return $tag;
	}

	/**
	 * Tag a given feed entry with the given tag.
	 *
	 * @param \Pascal\FeedDisplayerBundle\Entity\Tag $tag
	 * @param \Pascal\FeedGathererBundle\Entity\FeedEntry $feedEntry
	 * @return boolean
	 */
	public function tagFeedEntry(Tag $tag, \Pascal\FeedGathererBundle\Entity\FeedEntry $feedEntry)
	{
		$feedEntries = $tag->getFeedEntries()->toArray();
		if (in_array($tag, (array)$feedEntries))
			return true;

		$feedEntries[] = $feedEntry;
		$tag->setFeedEntries($feedEntries);

		try
		{
			$this->entityManager->persist($tag);
			$this->entityManager->flush();
		}
		catch(\Exception $e)
		{
			return false;
		}

		return true;
	}

	/**
	 * Removes tag from given feed entry.
	 *
	 * @param \Pascal\FeedDisplayerBundle\Entity\Tag $tag
	 * @param \Pascal\FeedGathererBundle\Entity\FeedEntry $feedEntry
	 * @return bool
	 */
	public function unTagFeedEntry(Tag $tag, \Pascal\FeedGathererBundle\Entity\FeedEntry $feedEntry)
	{
		$feedEntries = $tag->getFeedEntries()->toArray();
		$newFeedEntries = array();
		foreach ($feedEntries as $orgFeedEntry)
		{
			if ($orgFeedEntry != $feedEntry)
				$newFeedEntries[] = $feedEntry;
		}

		$tag->setFeedEntries($newFeedEntries);

		try
		{
			$this->entityManager->persist($tag);
			$this->entityManager->flush();
		}
		catch(\Exception $e)
		{
			return false;
		}

		return true;
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Entity\Tag[]
	 */
	protected function getTagsFromDatabase()
	{
		$tags = $this->entityManager->getRepository("PascalFeedDisplayerBundle:Tag")->findAll();
		return $tags;
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Entity\Tag[]
	 */
	protected function getTagsWithFeedEntriesFromDatabase()
	{
		$dql = '
			SELECT t
			FROM PascalFeedDisplayerBundle:Tag t
			JOIN t.feedEntries fe';

		$query = $this->entityManager->createQuery($dql);
		$tags = $query->execute();

		return $tags;
	}
}
