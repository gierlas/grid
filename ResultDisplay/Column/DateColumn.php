<?php

namespace Kora\Grid\ResultDisplay\Column;

use Kora\Grid\ResultDisplay\Column;


/**
 * Class DateColumn
 * @author Paweł Gierlasiński <pawel@mediamonks.com>
 */
class DateColumn extends Column
{
	/**
	 * @var string
	 */
	private $format;

	/**
	 * DateColumn constructor.
	 * @param string      $name
	 * @param string      $format
	 * @param string|null $type
	 * @param array       $extraConfig
	 */
	public function __construct($name, string $format = 'Y-m-d', $type = null, array $extraConfig = [])
	{
		parent::__construct($name, $type, $extraConfig);
		$this->format = $format;
		$this->init();
	}

	/**
	 * Init value formatter
	 */
	protected function init()
	{
		$this->valueFormatter = function ($value) {
			if (empty($value)) return null;

			if ($value instanceof \DateTime) {
				return $value->format($this->format);
			}

			if (is_string($value)) {
				$dateTimeObject = new \DateTime($value);
				return $dateTimeObject->format($this->format);
			}

			throw new \InvalidArgumentException("$value is not proper date.");
		};
	}
}