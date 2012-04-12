<?php

namespace Pascal\FeedDisplayerBundle\Service\FeedEntry;

class FeedEntryFilterSettings
{
	const DEFAULT_PAGE_SIZE = 25;

	private $page = 1;
	private $pageSize = self::DEFAULT_PAGE_SIZE;

	private $orderField;
	private $orderType;

	private $source;
	private $tagList;


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

	public function setSource($source)
	{
		$this->source = $source;
	}

	public function getSource()
	{
		return $this->source;
	}

	public function setTagList($tagList)
	{
		$this->tagList = $tagList;
	}

	public function getTagList()
	{
		return $this->tagList;
	}
}
