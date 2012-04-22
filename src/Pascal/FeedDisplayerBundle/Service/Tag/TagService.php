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

	public function tagFeedEntry(Tag $tag, \Pascal\FeedGathererBundle\Entity\FeedEntry $feedEntry)
	{
//		$tags = $feedEntry->getTags();
//		$tags[] = $tag;
//		$feedEntry->setTags($tags);

		$feedEntries = $tag->getFeedEntries();
		$feedEntries[] = $feedEntry;
		$tag->setFeedEntries($feedEntries);

		$this->entityManager->persist($tag);
		$this->entityManager->flush();
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Entity\Tag[]
	 */
	protected function getTagsFromDatabase()
	{
		$tags = $this->entityManager->getRepository("PascalFeedDisplayerBundle:Tag")->findAll();
		return $tags;
	}
}
