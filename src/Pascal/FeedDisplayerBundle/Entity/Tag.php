<?php

namespace Pascal\FeedDisplayerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pascal\FeedDisplayerBundle\Entity\Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pascal\FeedDisplayerBundle\Entity\TagRepository")
 */
class Tag implements \Serializable
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
	 * @var string $name
	 *
	 * @ORM\Column(name="tagname", type="string", length=255)
	 */
	private $name;

	/**
	 * @var \Pascal\FeedGathererBundle\Entity\FeedEntry[]
	 *
	 * @ORM\ManyToMany(targetEntity="\Pascal\FeedGathererBundle\Entity\FeedEntry", inversedBy="tags")
	 * @ORM\JoinTable(name="TaggedFeedEntries")
	 */
	private $feedEntries;

	public function __construct()
	{
		$this->feedEntries = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return \Pascal\FeedGathererBundle\Entity\FeedEntry[]
	 */
	public function getFeedEntries()
	{
		return $this->feedEntries;
	}

	/**
	 * @param array $feedEntries
	 */
	public function setFeedEntries($feedEntries)
	{
		$this->feedEntries = $feedEntries;
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * String representation of object
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or &null;
	 */
	public function serialize()
	{
		$serialized = serialize(array(
			$this->id,
			$this->name,
			$this->feedEntries->toArray(),
		));

		return $serialized;
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
			$this->name,
			$this->feedEntries,
			) = unserialize($serialized);
	}
}
