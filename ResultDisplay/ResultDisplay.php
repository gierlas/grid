<?php

namespace Kora\Grid\ResultDisplay;

use Kora\DataProvider\Result;
use Kora\Grid\ResultDisplay\DataAccessor\ObjectDataAccessor;


/**
 * Class ResultDisplay
 * @author Paweł Gierlasiński <gierlasinski.pawel@gmail.com>
 */
class ResultDisplay
{
	/**
	 * @var Result
	 */
	protected $result;

	/**
	 * @var DataAccessorInterface
	 */
	protected $dataAccessor;

	/**
	 * @var Column[]
	 */
	protected $columns = [];

	/**
	 * ResultDisplay constructor.
	 * @param DataAccessorInterface|null $dataAccessor
	 */
	public function __construct(DataAccessorInterface $dataAccessor = null)
	{
		$this->dataAccessor = $dataAccessor ?? new ObjectDataAccessor();
	}

	/**
	 * @param Result $result
	 */
	public function setResult(Result $result)
	{
		$this->result = $result;
	}

	/**
	 * @return Result
	 */
	public function getResult(): Result
	{
		return $this->result;
	}

	/**
	 * @param Column $column
	 * @return ResultDisplay
	 */
	public function addColumn(Column $column): ResultDisplay
	{
		if(!$column->isFake() && $column->getValueGetter() === null) {
			$column->setValueGetter($this->getDefaultDataCallback($column->getName()));
		}

		$this->columns[$column->getName()] = $column;
		return $this;
	}

	/**
	 * @param string $name
	 * @return Column|null
	 */
	public function getColumn(string $name)
	{
		return $this->columns[$name] ?? null;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasColumn(string $name): bool
	{
		return isset($this->columns[$name]);
	}

	/**
	 * @return Column[]
	 */
	public function getColumns(): array
	{
		return $this->columns;
	}

	/**
	 * @return DataAccessorInterface
	 */
	public function getDataAccessor(): DataAccessorInterface
	{
		return $this->dataAccessor;
	}

	/**
	 * @param string $name
	 * @return callable
	 */
	protected function getDefaultDataCallback(string $name): callable
	{
		return function ($data) use ($name) {
			return $this->dataAccessor->getData($name, $data);
		};
	}
}
