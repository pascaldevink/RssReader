<?php

namespace Pascal\FeedDisplayerBundle\Service\FeedEntry;

use \Symfony\Component\HttpFoundation\Request;

class FeedEntryFilterSettings
{
	const DEFAULT_PAGE_SIZE = 25;
	const ORDER_TYPE_ASC = 'ASC';
	const ORDER_TYPE_DESC = 'DESC';

	/**
	 * @var bool
	 */
	private $paging = true;

	private $page = 1;
	private $pageSize = self::DEFAULT_PAGE_SIZE;

	private $orderField = 'lastUpdateTime';
	private $orderType = self::ORDER_TYPE_DESC;

	/**
	 * @var \Pascal\FeedDisplayerBundle\Filter\LastUpdateTimeFilter
	 */
	private $lastUpdateTime;

	/**
	 * @var \Pascal\FeedDisplayerBundle\Filter\SourceFilter
	 */
	private $source;

	/**
	 * @var \Pascal\FeedDisplayerBundle\Filter\TagFilter
	 */
	private $tagList;

	public function __construct(Request $request)
	{
		$this->source = new \Pascal\FeedDisplayerBundle\Filter\SourceFilter(
			$request->get('source')
		);

		$this->tagList = new \Pascal\FeedDisplayerBundle\Filter\TagFilter(
			$request->get('tags')
		);

		try {
			$lastUpdateTimeValue = new \DateTime($request->get('lastUpdateTime', 'exception'));
		} catch (\Exception $e) {
			$lastUpdateTimeValue = null;
		}
		$this->lastUpdateTime = new \Pascal\FeedDisplayerBundle\Filter\LastUpdateTimeFilter(
			$lastUpdateTimeValue
		);
	}

	public function setOrderField($orderField)
	{
		$this->orderField = $orderField;
	}

	public function getOrderField()
	{
		return $this->orderField;
	}

	public function setOrderType($orderType)
	{
		$this->orderType = $orderType;
	}

	public function getOrderType()
	{
		return $this->orderType;
	}

	public function setPage($page)
	{
		$this->page = $page;
	}

	public function getPage()
	{
		return $this->page;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
	}

	public function getPageSize()
	{
		return $this->pageSize;
	}

	/**
	 * @param \Pascal\FeedDisplayerBundle\Filter\SourceFilter $source
	 */
	public function setSource($source)
	{
		$this->source = $source;
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Filter\SourceFilter
	 */
	public function getSource()
	{
		return $this->source;
	}

	/**
	 * @param \Pascal\FeedDisplayerBundle\Filter\TagFilter $tagList
	 */
	public function setTagList($tagList)
	{
		$this->tagList = $tagList;
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Filter\TagFilter
	 */
	public function getTagList()
	{
		return $this->tagList;
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Filter\LastUpdateTimeFilter
	 */
	public function getLastUpdateTime()
	{
		return $this->lastUpdateTime;
	}

	/**
	 * @param \Pascal\FeedDisplayerBundle\Filter\LastUpdateTimeFilter $lastUpdateTime
	 */
	public function setLastUpdateTime($lastUpdateTime)
	{
		$this->lastUpdateTime = $lastUpdateTime;
	}

	/**
	 * @param boolean $paging
	 */
	public function setPaging($paging)
	{
		$this->paging = $paging;
	}

	/**
	 * @return boolean
	 */
	public function getPaging()
	{
		return $this->paging;
	}
}
