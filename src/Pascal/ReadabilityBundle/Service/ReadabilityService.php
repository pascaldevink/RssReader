<?php

namespace Pascal\ReadabilityBundle\Service;

use \Symfony\Component\DependencyInjection\Exception\RuntimeException;

class ReadabilityService
{
	const readabilityShortenerUrl = 'http://www.readability.com/api/shortener/v1/urls';

	private $logger;

	public function __construct($logger)
	{
		$this->logger = $logger;
	}

	public function getShortenedUrl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, static::readabilityShortenerUrl);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('url'=>$url));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);
		if ($result === false)
		{
			throw new RuntimeException(curl_error($ch), curl_errno($ch));
		}
		curl_close($ch);

		$result = json_decode($result);
		return $result->meta->rdd_url;
	}
}
