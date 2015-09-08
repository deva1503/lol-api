<?php

namespace LoLApi\Cache;

use Doctrine\Common\Cache\FileCache;

class fhfFileCache extends FileCache
{
	const	EXTENSION	= '.data';

	/**
	 * {@inheritdoc}
	 */
	public function __construct($directory, $extension = self::EXTENSION, $umask = 0002)
	{
		parent::__construct($directory, $extension, $umask);
	}

	private function _filename($id)
	{
		$hh	= hash('sha256', $id);
		return $this->directory .'/'
			. $hh[0] .'/'. $hh[1] .'/'. $hh
			. $this->extension;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doFetch($id)
	{
		$data		= '';
		$lifetime	= -1;
		$filename	= self::_filename($id);

		if (!is_file($filename))
			return false;

		$resource	= fopen($filename, "r");
		if (false !== ($line = fgets($resource)))
			$lifetime = (integer) $line;

		if ($lifetime !== 0 && $lifetime < time())
		{
			fclose($resource);
			return false;
		}

		while (false !== ($line = fgets($resource)))
		{
			$data .= $line;
		}

		fclose($resource);

		return unserialize($data);
	}

	/**
	* {@inheritdoc}
	*/
	protected function doContains($id)
	{
		$lifetime	= -1;
		$filename	= self::_filename($id);

		if (!is_file($filename))
			return false;

		$resource	= fopen($filename, 'r');
		if (false !== ($line = fgets($resource)))
			$lifetime = (integer)$line;

		fclose($resource);

		return $lifetime === 0 || $lifetime > time();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doSave($id, $data, $lifeTime = 0)
	{
		if ($lifeTime > 0)
			$lifeTime = time() + $lifeTime;

		$data		= serialize($data);
		$filename	= self::_filename($id);
		return $this->writeFile($filename, $lifeTime . PHP_EOL . $data);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function doDelete($id)
	{
		return @unlink(self::_filename($id));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function doGetStats()
	{
		$usage	= 0;
		foreach ($this->getIterator() as $file)
			$usage += $file->getSize();

		$free	= disk_free_space($this->directory);

		return array(
			Cache::STATS_HITS				=> null,
			Cache::STATS_MISSES				=> null,
			Cache::STATS_UPTIME				=> null,
			Cache::STATS_MEMORY_USAGE		=> $usage,
			Cache::STATS_MEMORY_AVAILABLE	=> $free,
		);
	}
}
