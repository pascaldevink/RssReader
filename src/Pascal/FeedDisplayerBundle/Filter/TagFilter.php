<?php

namespace Pascal\FeedDisplayerBundle\Filter;

class TagFilter
{
	private $value;
	private $isSet = false;

	public function __construct($value)
	{
		if (!is_null($value) && !empty($value) && is_array($value))
		{
			$this->value = $value;
			$this->isSet = true;
		}
	}

	public function wasSet()
	{
		return $this->isSet;
	}

	public function getValue()
	{
		return $this->value;
	}

}
