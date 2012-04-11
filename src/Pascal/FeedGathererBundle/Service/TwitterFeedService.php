<?php

namespace Pascal\FeedGathererBundle\Service;

use \Pascal\FeedGathererBundle\Entity\TwitterUser;

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

	public function __construct(\Symfony\Bundle\DoctrineBundle\Registry $doctrine, \tmhOAuth $twitter)
	{
		$this->entityManager = $doctrine->getEntityManager();
		$this->twitter = $twitter;
	}

	public function getServiceType()
	{
		return 'twitter';
	}

	public function downloadFeed(\Pascal\FeedGathererBundle\Entity\Feed $feed, \DateTime $lastUpdateTime)
	{
		$twitter = $this->getTwitter();
		$twitterUser = $this->getTwitterUser($feed);

		$twitter->setConfigValue('user_token', $twitterUser->getOauthToken());
		$twitter->setConfigValue('user_secret', $twitterUser->getOauthTokenSecret());

		$code = $twitter->request('GET', $twitter->url('1/statuses/home_timeline'), array(
			'include_entities' => true
		));

		$response = $twitter->getResponse();
		if ($code == 200)
		{
			$timeline = json_decode($response['response'], true);
			$this->processItems($timeline, $twitterUser, $lastUpdateTime);
		}
	}

	/**
	 * @return \Pascal\FeedGathererBundle\Entity\TwitterUser[]
	 */
	protected function getTwitterUser(\Pascal\FeedGathererBundle\Entity\Feed $feed)
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
	 * @return \tmhOAuth
	 */
	protected function getTwitter()
	{
		$twitter = $this->twitter;
		$twitter->load(self::$config);
		return $twitter;
	}

	protected function processItems($items, TwitterUser $twitterUser, \DateTime $lastUpdateTime)
	{
		foreach($items as $item)
		{
			// FIXME: Add time check
			// TODO: ADD whitelist/blacklist user check
			// TODO: ADD used link check

			$urls = $item['entities']['urls'];
			if (empty($urls))
				continue;

			$author = $item['user']['name'];
			$text = $item['text'];
			$createdAt = $item['created_at'];
			$dateTime = new \DateTime($createdAt);

			foreach($urls as $url)
			{
				$feedEntry = new \Pascal\FeedGathererBundle\Entity\FeedEntry();
				$feedEntry->setAuthor($author);
				$feedEntry->setDescription($text);
				$feedEntry->setTitle('Link shared by ' . $author);
				$feedEntry->setUrl($url['expanded_url']);
				$feedEntry->setLastUpdateTime($dateTime);

				$this->entityManager->persist($feedEntry);
			}
		}

		$this->entityManager->flush();
	}
}
