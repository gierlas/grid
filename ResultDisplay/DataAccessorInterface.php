<?php

namespace Kora\Grid\ResultDisplay;


/**
 * Interface DataAccessorInterface
 * @author Paweł Gierlasiński <gierlasinski.pawel@gmail.com>
 */
interface DataAccessorInterface
{
	public function getData(string $path, $source);
}