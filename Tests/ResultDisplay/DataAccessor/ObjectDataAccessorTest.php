<?php

namespace Kora\Grid\Tests\ResultDisplay\DataAccessor;

use Kora\Grid\ResultDisplay\DataAccessor\ObjectDataAccessor;
use Kora\Grid\Tests\Fixture\Foo;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectDataAccessorTest
 * @author Paweł Gierlasiński <pawel@mediamonks.com>
 */
class ObjectDataAccessorTest extends TestCase
{
	/**
	 * @dataProvider getDataProvider
	 * @param string $path
	 * @param        $source
	 * @param        $expectedValue
	 */
	public function testGetData(string $path, $source, $expectedValue)
	{
		$objectDataAccessor = new ObjectDataAccessor();
		$value = $objectDataAccessor->getData($path, $source);

		$this->assertEquals($expectedValue, $value);
	}

	public function getDataProvider()
	{
		$subSub = new \stdClass();
		$subSub->wow = "wow";

		$sub = new \stdClass();
		$sub->name = "test";
		$sub->coll = ["one", "two", "three"];

		$sub1 = new \stdClass();
		$sub1->name = "test";
		$sub1->coll = ["one", "two", "three"];

		$obj = new \stdClass();
		$obj->foo = "name";
		$obj->bar = ["four", "five", "six"];
		$obj->sub = [$sub, $sub1];

		$foo = new Foo('foo', 'bar', 'test');

		return [
			['0', ['asdf'], 'asdf'],
			['0', [], null],
			['foo', $obj, $obj->foo],
			['sub.0.name', $obj, $obj->sub[0]->name],
			['sub.0.coll.2', $obj, $obj->sub[0]->coll[2]],
			['foo', $foo, null],
			['bar', $foo, 'bar'],
			['test', $foo, 'test']
		];
	}
}
