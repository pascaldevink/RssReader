<?php

namespace Pascal\FeedGathererBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pascal\FeedGathererBundle\Entity\FeedEntry
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pascal\FeedGathererBundle\Entity\FeedEntryRepository")
 */
class FeedEntry implements \Serializable
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
     * @var text $title
     *
     * @ORM\Column(name="title", type="text")
     */
    private $title;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string $author
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var datetime $lastUpdateTime
     *
     * @ORM\Column(name="lastUpdateTime", type="datetime")
     */
    private $lastUpdateTime;

	/**
	 * @var Feed $feed
	 *
	 * @ORM\ManyToOne(targetEntity="Feed", inversedBy="entries")
	 * @ORM\JoinColumn(name="feed_id", referencedColumnName="id")
	 */
	private $feed;

	/**
	 * @var \Pascal\FeedDisplayerBundle\Entity\Tag[]
	 *
	 * @ORM\ManyToMany(targetEntity="\Pascal\FeedDisplayerBundle\Entity\Tag", mappedBy="feedEntries")
	 */
	private $tags;

	public function __construct()
	{
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param text $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return text 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set author
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lastUpdateTime
     *
     * @param datetime $lastUpdateTime
     */
    public function setLastUpdateTime($lastUpdateTime)
    {
        $this->lastUpdateTime = $lastUpdateTime;
    }

    /**
     * Get lastUpdateTime
     *
     * @return datetime 
     */
    public function getLastUpdateTime()
    {
        return $this->lastUpdateTime;
    }

    /**
     * Set feed
     *
     * @param Pascal\FeedGathererBundle\Entity\Feed $feed
     */
    public function setFeed(\Pascal\FeedGathererBundle\Entity\Feed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * Get feed
     *
     * @return Pascal\FeedGathererBundle\Entity\Feed 
     */
    public function getFeed()
    {
        return $this->feed;
    }

	/**
	 * @param array $tags
	 */
	public function setTags($tags)
	{
		$this->tags = $tags;
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Entity\Tag[]
	 */
	public function getTags()
	{
		return $this->tags;
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
			$this->author,
			$this->description,
			$this->feed,
			$this->lastUpdateTime,
			$this->title,
			$this->url,
			$this->tags,
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
			$this->author,
			$this->description,
			$this->feed,
			$this->lastUpdateTime,
			$this->title,
			$this->url,
			$this->tags,
			) = unserialize($serialized);
	}
}