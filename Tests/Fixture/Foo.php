<?php

namespace Kora\Grid\Tests\Fixture;


/**
 * Class Foo
 * @author Paweł Gierlasiński <pawel@mediamonks.com>
 */
class Foo
{
	protected $foo;

	public $bar;

	protected $test;

	public function __construct($foo, $bar, $test)
	{
		$this->foo = $foo;
		$this->bar = $bar;
		$this->test = $test;
	}

	public function getTest()
	{
		return $this->test;
	}
}