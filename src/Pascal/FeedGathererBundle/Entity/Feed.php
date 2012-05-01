<?php

namespace Pascal\FeedGathererBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pascal\FeedGathererBundle\Entity\Feed
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pascal\FeedGathererBundle\Entity\FeedRepository")
 */
class Feed implements \Serializable
{
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string $type
	 *
	 * @ORM\Column(name="type", type="string", length=255)
	 */
	private $type;

	/**
	 * @var integer $type
	 *
	 * @ORM\Column(name="typeId", type="integer")
	 */
	private $typeId;

	/**
	 * @var \DateTime $disabled
	 *
	 * @ORM\Column(name="lastUpdateTime", type="datetime")
	 */
	private $lastUpdateTime;

	/**
	 * @var boolean $disabled
	 *
	 * @ORM\Column(name="disabled", type="boolean")
	 */
	private $disabled;

	/**
	 * @var FeedEntry[]
	 *
	 * @ORM\OneToMany(targetEntity="FeedEntry", mappedBy="feed")
	 */
	private $entries;

	public function __construct()
	{
		$this->entries = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set url
	 *
	 * @param string $url
	 */
	public function setType($url)
	{
		$this->type = $url;
	}

	/**
	 * Get url
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Add entries
	 *
	 * @param \Pascal\FeedGathererBundle\Entity\FeedEntry $entries
	 */
	public function addFeedEntry(\Pascal\FeedGathererBundle\Entity\FeedEntry $entries)
	{
		$this->entries[] = $entries;
	}

	/**
	 * Get entries
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getEntries()
	{
		return $this->entries;
	}

	/**
	 * Set disabled
	 *
	 * @param boolean $disabled
	 */
	public function setDisabled($disabled)
	{
		$this->disabled = $disabled;
	}

	/**
	 * Get disabled
	 *
	 * @return boolean
	 */
	public function getDisabled()
	{
		return $this->disabled;
	}

	/**
	 * @param int $typeId
	 */
	public function setTypeId($typeId)
	{
		$this->typeId = $typeId;
	}

	/**
	 * @return int
	 */
	public function getTypeId()
	{
		return $this->typeId;
	}

	/**
	 * @param \DateTime $lastUpdateTime
	 */
	public function setLastUpdateTime($lastUpdateTime)
	{
		$this->lastUpdateTime = $lastUpdateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getLastUpdateTime()
	{
		return $this->lastUpdateTime;
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * String representation of object
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or &null;
	 */
	public function serialize()
	{
		return serialize(array(
			$this->id,
			$this->disabled,
			$this->type,
			$this->typeId,
			$this->lastUpdateTime->getTimestamp(),
		));
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Constructs the object
	 * @link http://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized <p>
	 * The string representation of the object.
	 * </p>
	 * @return mixed the original value unserialized.
	 */
	public function unserialize($serialized)
	{
		list(
			$this->id,
			$this->disabled,
			$this->type,
			$this->typeId,
			$lastUpdateTime,
			) = unserialize($serialized);
		$this->lastUpdateTime = \DateTime::createFromFormat('U', $lastUpdateTime);
	}
}