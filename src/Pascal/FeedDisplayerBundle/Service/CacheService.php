<?php

namespace Pascal\FeedDisplayerBundle\Service;

use \Symfony\Component\HttpKernel\KernelInterface;

class CacheService
{
	private static $instance;

	/**
	 * @var \Symfony\Component\HttpKernel\KernelInterface
	 */
	private $kernel;
	private $memcached;

	protected function __construct()
	{
		$this->memcached = new \Memcached();
		$this->memcached->addServer('localhost', 11211);

		$this->memcached->flush();
	}

	public static function getInstance()
	{
		if (self::$instance == null)
			self::$instance = new CacheService();

		return self::$instance;
	}

	public function set($key, $value)
	{
		if ($this->kernel->isDebug())
			return;

		try {
			$this->memcached->set($key, $value, 300);
		}
		catch (\Exception $e)
		{
			var_dump($value[0]->serialize());
			var_dump('Settings ' . $key);
			var_dump($this->memcached->getResultCode());
			var_dump($e->getMessage());
		}
	}

	public function get($key)
	{
		if ($this->kernel->isDebug())
			return null;

		try {
			return $this->memcached->get($key);
		}
		catch (\Exception $e)
		{
			var_dump('Getting ' . $key);
			var_dump($this->memcached->getResultCode());
			var_dump($e->getMessage());
		}
	}

	public function setKernel(KernelInterface $kernel)
	{
		$this->kernel = $kernel;
	}
}
