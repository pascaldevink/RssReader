<?php

namespace Pascal\FeedGathererBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pascal\FeedGathererBundle\Entity\RssFeed
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pascal\FeedGathererBundle\Entity\RssFeedRepository")
 */
class RssFeed
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

	/**
	 * @var string $url
	 *
	 * @ORM\Column(name="url", type="string", length=255)
	 */
	private $url;


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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

	/**
	 * @param string $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}
}