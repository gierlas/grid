<?php

namespace Kora\Grid\ResultDisplay;


/**
 * Interface DataAccessorInterface
 * @author Paweł Gierlasiński <gierlasinski.pawel@gmail.com>
 */
interface DataAccessorInterface
{
	/**
	 * @param array $mapping
	 */
	public function setMapping(array $mapping);

	/**
	 * @param string $path
	 * @param        $source
	 * @return mixed
	 */
	public function getData(string $path, $source);
}