<?php

namespace Kora\Grid\ResultDisplay\DataAccessor;

use Kora\Grid\ResultDisplay\DataAccessorInterface;


/**
 * Class ObjectDataAccessor
 * @author Paweł Gierlasiński <gierlasinski.pawel@gmail.com>
 */
class ObjectDataAccessor implements DataAccessorInterface
{
	protected $mapping = [];

	/**
	 * @param array $mapping
	 */
	public function setMapping(array $mapping)
	{
		$this->mapping = $mapping;
	}

	/**
	 * @param string $path
	 * @param        $source
	 * @return mixed
	 */
	public function getData(string $path, $source)
	{
		if (isset($this->mapping[$path])) {
			$path = $this->mapping[$path];
		}

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

		$reflectionClass = !($source instanceof \stdClass) ? new \ReflectionClass($source) : null;

		$getter = 'get' . ucfirst($currentNode);
		if ($this->isMethodAccessible($getter, $source, $reflectionClass)) {
			$value = $source->{$getter}();
		} else if ($this->isPropertyAccessible($currentNode, $source, $reflectionClass)) {
			$value = $source->{$currentNode};
		} else {
			return null;
		}

		return $this->getDataRecursive($nodes, $value);
	}

	/**
	 * @param string $method
	 * @param        $source
	 * @param        $reflectionClass
	 * @return bool
	 */
	protected function isMethodAccessible(string $method, $source, $reflectionClass): bool
	{
		return method_exists($source, $method) //stdClass
			&& (!$reflectionClass || $reflectionClass->getMethod($method)->isPublic()); //If object is not stdClass check accessibility
	}

	/**
	 * @param string $property
	 * @param        $source
	 * @param        $reflectionClass
	 * @return bool
	 */
	protected function isPropertyAccessible(string $property, $source, $reflectionClass): bool
	{
		return property_exists($source, $property) //stdClass
			&& (!$reflectionClass || $reflectionClass->getProperty($property)->isPublic()); //If object is not stdClass check accessibility
	}

	/**
	 * @param array $nodes
	 * @param       $source
	 * @return mixed|null
	 */
	protected function handleArray(array $nodes, $source)
	{
		$currentNode = array_shift($nodes);

		return isset($source[$currentNode])
			? $this->getDataRecursive($nodes, $source[$currentNode])
			: null;
	}
}