<?php

namespace Pascal\FeedGathererBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pascal\FeedGathererBundle\Entity\TwitterUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pascal\FeedGathererBundle\Entity\TwitterUserRepository")
 */
class TwitterUser
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
	 * @var string $oauthToken
	 *
	 * @ORM\Column(name="oauthToken", type="string", length=255)
	 */
	private $oauthToken;

	/**
	 * @var string $oauthTokenSecret
	 *
	 * @ORM\Column(name="oauthTokenSecret", type="string", length=255)
	 */
	private $oauthTokenSecret;

	/**
	 * @var integer $userId
	 *
	 * @ORM\Column(name="userId", type="integer")
	 */
	private $userId;

	/**
	 * @var string $screenName
	 *
	 * @ORM\Column(name="screenName", type="string", length=255)
	 */
	private $screenName;

	/**
	 * @var string $blacklistUsernames
	 *
	 * @ORM\Column(name="blacklistUsernames", type="text")
	 */
	private $blacklistUsernames;

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
	 * Set oauthToken
	 *
	 * @param string $oauthToken
	 */
	public function setOauthToken($oauthToken)
	{
		$this->oauthToken = $oauthToken;
	}

	/**
	 * Get oauthToken
	 *
	 * @return string
	 */
	public function getOauthToken()
	{
		return $this->oauthToken;
	}

	/**
	 * Set oauthTokenSecret
	 *
	 * @param string $oauthTokenSecret
	 */
	public function setOauthTokenSecret($oauthTokenSecret)
	{
		$this->oauthTokenSecret = $oauthTokenSecret;
	}

	/**
	 * Get oauthTokenSecret
	 *
	 * @return string
	 */
	public function getOauthTokenSecret()
	{
		return $this->oauthTokenSecret;
	}

	/**
	 * Set userId
	 *
	 * @param integer $userId
	 */
	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	/**
	 * Get userId
	 *
	 * @return integer
	 */
	public function getUserId()
	{
		return $this->userId;
	}

	/**
	 * Set screenName
	 *
	 * @param string $screenName
	 */
	public function setScreenName($screenName)
	{
		$this->screenName = $screenName;
	}

	/**
	 * Get screenName
	 *
	 * @return string
	 */
	public function getScreenName()
	{
		return $this->screenName;
	}

	/**
	 * @param array $blacklistUsernames
	 */
	public function setBlacklistUsernames($usernames)
	{
		$this->blacklistUsernames = implode(',', $usernames);
	}

	/**
	 * @return array
	 */
	public function getBlacklistUsernames()
	{
		$usernames = array();
		if (!empty($this->blacklistUsernames))
			$usernames = explode(',', $this->blacklistUsernames);

		return $usernames;
	}
}