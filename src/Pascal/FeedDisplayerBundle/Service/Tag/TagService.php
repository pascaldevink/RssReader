<?php

namespace Pascal\FeedDisplayerBundle\Service\Tag;

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
	 * @return \Pascal\FeedDisplayerBundle\Entity\Tag[]
	 */
	protected function getTagsFromDatabase()
	{
		$tags = $this->entityManager->getRepository("PascalFeedDisplayerBundle:Tag")->findAll();
		return $tags;
	}
}
