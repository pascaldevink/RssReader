<?php

namespace Pascal\FeedDisplayerBundle\Entity;

class ItemResult
{

	/**
	 * @var array
	 */
	private $itemList;

	/**
	 * @var int
	 */
	private $totalCount;

	/**
	 * @var int
	 */
	private $filteredCount;

	/**
	 * @param int $filteredCount
	 */
	public function setFilteredCount($filteredCount)
	{
		$this->filteredCount = $filteredCount;
	}

	/**
	 * @return int
	 */
	public function getFilteredCount()
	{
		return $this->filteredCount;
	}

	/**
	 * @param array $itemList
	 */
	public function setItemList($itemList)
	{
		$this->itemList = $itemList;
	}

	/**
	 * @return array
	 */
	public function getItemList()
	{
		return $this->itemList;
	}

	/**
	 * @param int $totalCount
	 */
	public function setTotalCount($totalCount)
	{
		$this->totalCount = $totalCount;
	}

	/**
	 * @return int
	 */
	public function getTotalCount()
	{
		return $this->totalCount;
	}
}
