<?php

namespace Kora\Grid\Tests\ResultDisplay;

use Kora\Grid\ResultDisplay\Column;
use Kora\Grid\ResultDisplay\DataAccessorInterface;
use Kora\Grid\ResultDisplay\ResultDisplay;
use PHPUnit\Framework\TestCase;
use Mockery as m;

/**
 * Class ResultDisplayTest
 * @author Paweł Gierlasiński <pawel@mediamonks.com>
 */
class ResultDisplayTest extends TestCase
{
	use m\Adapter\Phpunit\MockeryPHPUnitIntegration;

	public function testColumnProvideDefaultDataCallback()
	{
		$columnName = 'test';
		$column = m::mock(Column::class, [$columnName])
			->shouldDeferMissing();

		$column->setFake(false);

		$column
			->shouldReceive('setValueGetter')
			->once();

		$resultDisplay = m::mock(ResultDisplay::class, [m::mock(DataAccessorInterface::class)])
			->shouldDeferMissing();

		$resultDisplay
			->addColumn($column);

		$this->assertTrue($resultDisplay->hasColumn($columnName));
		$this->assertEquals($resultDisplay->getColumn($columnName), $column);
	}
}
