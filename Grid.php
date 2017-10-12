<?php

namespace Kora\Grid;

use Kora\DataProvider\DataProviderInterface;
use Kora\DataProvider\DataProviderOperatorsSetup;
use Kora\DataProvider\Result;
use Kora\Grid\ResultDisplay\Column;
use Kora\Grid\ResultDisplay\ResultDisplay;


/**
 * Class Grid
 * @author Paweł Gierlasiński <gierlasinski.pawel@gmail.com>
 */
class Grid extends DataProviderOperatorsSetup
{
	/**
	 * @var ResultDisplay
	 */
	protected $resultDisplay;

	/**
	 * @var string[]
	 */
	protected $orderColumns = [];

	/**
	 * Grid constructor.
	 */
	public function __construct()
	{
		$this->resultDisplay = new ResultDisplay();
	}

	/**
	 * @param Column $column
	 * @return Grid
	 */
	public function addColumn(Column $column): Grid
	{
		$this->resultDisplay->addColumn($column);

		if($column->isSortable()) {
			$this->orderColumns[] = $column->getName();
		}

		return $this;
	}

	/**
	 * @return ResultDisplay
	 */
	public function getResultDisplay(): ResultDisplay
	{
		return $this->resultDisplay;
	}

	/**
	 * @param DataProviderInterface $dataProvider
	 * @param array                 $params
	 * @return Result
	 */
	public function fetchData(DataProviderInterface $dataProvider, array $params = []): Result
	{
		if($this->order !== null) {
			$this->order->setOrderColumns($this->orderColumns);
		}

		$this->setData($params);

		$result = $dataProvider->fetchData($this);

		$this->resultDisplay->setResult($result);

		return $result;
	}
}