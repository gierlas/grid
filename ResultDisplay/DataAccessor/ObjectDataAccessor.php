<?php

namespace Kora\Grid\ResultDisplay\DataAccessor;

use Kora\Grid\ResultDisplay\DataAccessorInterface;


/**
 * Class ObjectDataAccessor
 * @author Paweł Gierlasiński <gierlasinski.pawel@gmail.com>
 */
class ObjectDataAccessor implements DataAccessorInterface
{
	/**
	 * @param string $path
	 * @param        $source
	 * @return mixed
	 */
	public function getData(string $path, $source)
	{
		$nodes = explode('.', $path);
		return $this->getDataRecursive($nodes, $source);
	}

	/**
	 * @param array $nodes
	 * @param       $source
	 * @return mixed
	 */
	public function getDataRecursive(array $nodes, $source)
	{
		if (empty($nodes)) return $source;

		if (is_object($source) && !$source instanceof \ArrayAccess) {
			return $this->handleObject($nodes, $source);
		}

		if (is_array($source)) {
			return $this->handleArray($nodes, $source);
		}
	}

	/**
	 * @param array $nodes
	 * @param       $source
	 * @return mixed|null
	 */
	protected function handleObject(array $nodes, $source)
	{
		$currentNode = array_shift($nodes);

		$reflectionClass = null;
		if (!($source instanceof \stdClass)) {
			$reflectionClass = new \ReflectionClass($source);
		}

		$getter = 'get' . ucfirst($currentNode);
		if (
			method_exists($source, $getter) //stdClass
			&& (!$reflectionClass || $reflectionClass->getMethod($getter)->isPublic()) //If object is not stdClass check accessibility
		) {
			$value = $source->{$getter}();
			return $this->getDataRecursive($nodes, $value);
		}

		if (
			property_exists($source, $currentNode) //stdClass
			&& (!$reflectionClass || $reflectionClass->getProperty($currentNode)->isPublic()) //If object is not stdClass check accessibility
		) {
			$value = $source->{$currentNode};
			return $this->getDataRecursive($nodes, $value);
		}

		return null;
	}

	/**
	 * @param array $nodes
	 * @param       $source
	 * @return mixed|null
	 */
	protected function handleArray(array $nodes, $source)
	{
		$currentNode = array_shift($nodes);

		if (isset($source[$currentNode])) {
			return $this->getDataRecursive($nodes, $source[$currentNode]);
		}

		return null;
	}
}