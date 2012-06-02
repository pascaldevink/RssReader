<?php

namespace Pascal\FeedGathererBundle\Service;

use \Pascal\FeedGathererBundle\Entity\TwitterUser;
use \Pascal\FeedGathererBundle\Entity\Feed;

class TwitterFeedService implements FeedService
{

	private static $config = array(
		'consumer_key' => 'BlR9tlw0CDzFSLcMJuMhRA',
		'consumer_secret' => 'yD9DjMKXudMMWz7Yff61pFDGo0mmSZjNZzgjqOxjPQ'
	);

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 * @var \tmhOAuth
	 */
	protected $twitter;

	/**
	 * @var \Symfony\Bridge\Monolog\Logger
	 */
	protected $logger;

	public function __construct()
	{
	}

	public function getServiceType()
	{
		return 'twitter';
	}

	public function downloadFeed(Feed $feed, \DateTime $lastUpdateTime)
	{
		$twitter = $this->getTwitter();
		$twitterUser = $this->getTwitterUser($feed);

		$twitter->setConfigValue('user_token', $twitterUser->getOauthToken());
		$twitter->setConfigValue('user_secret', $twitterUser->getOauthTokenSecret());

		$code = $twitter->request('GET', $twitter->url('1/statuses/home_timeline'), array(
			'include_entities' => true
		));

		$numberOfItems = 0;
		$response = $twitter->getResponse();
		if ($code == 200)
		{
			$timeline = json_decode($response['response'], true);
			$feedEntries = $this->processItems($timeline, $twitterUser, $feed, $lastUpdateTime);
			$numberOfItems = count($feedEntries);
			$this->saveItems($feedEntries);
		}
		else
		{
			$this->logger->err("Something went wrong in the twitter communication ($code): $response");
		}

		return $numberOfItems;
	}

	/**
	 * @param \Pascal\FeedGathererBundle\Entity\Feed $feed
	 * @return \Pascal\FeedGathererBundle\Entity\TwitterUser
	 */
	public function getTwitterUser(Feed $feed)
	{
		$dql = "
			SELECT t
			FROM PascalFeedGathererBundle:TwitterUser t
			WHERE t.id=:id";

		$query = $this->entityManager
			->createQuery($dql)
			->setParameter("id", $feed->getTypeId());

		$twitterUser = $query->getSingleResult();
		return $twitterUser;
	}

	/**
	 * Process all twitter entries by checking them for links.
	 *
	 * @param array $items
	 * @param \Pascal\FeedGathererBundle\Entity\TwitterUser $twitterUser
	 * @param \Pascal\FeedGathererBundle\Entity\Feed $feed
	 * @param \DateTime $lastUpdateTime
	 */
	protected function processItems($items, TwitterUser $twitterUser, Feed $feed, \DateTime $lastUpdateTime)
	{
		$feedEntries = array();
		$blacklistUsernames = $twitterUser->getBlacklistUsernames();

		foreach($items as $item)
		{
			// TODO: ADD used link check

			$username = $item['user']['screen_name'];
			$createdAt = $item['created_at'];

			// whitelist/blacklist user check
			if ($this->isUserOnBlacklist($blacklistUsernames, strtolower($username)))
			{
				$this->logger->info("Ignored a tweet from $username from $createdAt");
				continue;
			}

			$urls = $item['entities']['urls'];
			if (empty($urls))
				continue;

			$author = $item['user']['name'];
			$text = $item['text'];
			$dateTime = new \DateTime($createdAt);

//			if ($dateTime < $lastUpdateTime)
//				continue;

			foreach($urls as $url)
			{
				$feedEntry = new \Pascal\FeedGathererBundle\Entity\FeedEntry();
				$feedEntry->setAuthor($author);
				$feedEntry->setDescription($text);
				$feedEntry->setTitle('Link shared by ' . $author);
				$feedEntry->setUrl($url['expanded_url']);
				$feedEntry->setLastUpdateTime($dateTime);
				$feedEntry->setFeed($feed);

				$feedEntries[] = $feedEntry;
			}
		}

		return $feedEntries;
	}

	/**
	 * Save the given FeedEntry items
	 * @param \Pascal\FeedGathererBundle\Entity\FeedEntry[]  $items
	 */
	protected function saveItems(array $items)
	{
		foreach($items as $feedEntry)
		{
			$this->entityManager->persist($feedEntry);
		}

		$this->entityManager->flush();
	}

	/**
	 * Return whether or not if the given username exists in the array of blacklisted usernames.
	 *
	 * @param array $blacklist
	 * @param string $username
	 * @return bool
	 */
	protected function isUserOnBlacklist($blacklist, $username)
	{
		if (is_null($blacklist) || !is_array($blacklist))
			return false;

		return in_array($username, $blacklist);
	}

	/**
	 * @param \Symfony\Bundle\DoctrineBundle\Registry $doctrine
	 */
	public function setEntityManager(\Symfony\Bundle\DoctrineBundle\Registry $doctrine)
	{
		$this->entityManager = $doctrine->getEntityManager();
	}

	/**
	 * @param \tmhOAuth $twitter
	 */
	public function setTwitter($twitter)
	{
		$this->twitter = $twitter;
	}

	/**
	 * @return \tmhOAuth
	 */
	protected function getTwitter()
	{
		$twitter = $this->twitter;
		$twitter->load(self::$config);
		return $twitter;
	}

	/**
	 * @param \Symfony\Bridge\Monolog\Logger $logger
	 */
	public function setLogger(\Symfony\Bridge\Monolog\Logger $logger)
	{
		$this->logger = $logger;
	}
}
