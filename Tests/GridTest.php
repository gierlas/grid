<?php

namespace Kora\Grid\Tests;

use Kora\DataProvider\DataProviderInterface;
use Kora\DataProvider\Mapper;
use Kora\DataProvider\OperatorDefinition\OrderOperatorDefinitionInterface;
use Kora\DataProvider\Result;
use Kora\Grid\FormBuilder\FormBuilder;
use Kora\Grid\Grid;
use Kora\Grid\ResultDisplay\Column;
use PHPUnit\Framework\TestCase;
use Mockery as m;

/**
 * Class GridTest
 * @author Paweł Gierlasiński <pawel@mediamonks.com>
 */
class GridTest extends TestCase
{
	use m\Adapter\Phpunit\MockeryPHPUnitIntegration;

	public function testApplySortedColumnsToOrderOperator()
	{
		$columnName = 'test';
		$params = [];

		$column = (new Column($columnName))
			->setSortable(true);

		$order = m::mock(OrderOperatorDefinitionInterface::class);
		$order
			->shouldReceive('setOrderColumns')
			->with([$columnName])
			->once();

		$grid = m::mock(Grid::class, [m::mock(FormBuilder::class)])
			->shouldDeferMissing();

		$grid
			->addColumn($column)
			->setOrder($order);

		$dataProvider = m::mock(DataProviderInterface::class);
		$dataProvider
			->shouldReceive('fetchData')
			->andReturn(m::mock(Result::class))
			->once();

		$dataProvider
			->shouldReceive('getMapper')
			->andReturn(new Mapper())
			->once();

		$grid
			->shouldReceive('setData')
			->with($params)
			->once();

		$grid->fetchData($dataProvider, $params);
	}
}
