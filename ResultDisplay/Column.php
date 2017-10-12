<?php

namespace Kora\Grid\ResultDisplay;


/**
 * Class Column
 * @author Paweł Gierlasiński <gierlasinski.pawel@gmail.com>
 */
class Column
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string|null
	 */
	private $type;

	/**
	 * @var array
	 */
	protected $extraConfig;

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var bool
	 */
	protected $sortable = false;

	/**
	 * @var bool
	 */
	protected $fake = false;

	/**
	 * @var callable|null
	 */
	protected $valueGetter;

	/**
	 * @var callable|null
	 */
	protected $valueFormatter;

	/**
	 * @var callable|null
	 */
	protected $shouldDisplayCallback;

	/**
	 * Column constructor.
	 * @param string      $name
	 * @param string|null $type
	 * @param array       $extraConfig
	 */
	public function __construct(string $name, $type = null, array $extraConfig = [])
	{
		$this->name = $name;
		$this->type = $type;
		$this->extraConfig = $extraConfig;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return null|string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param null|string $type
	 * @return Column
	 */
	public function setType($type): Column
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label ?? ucfirst($this->name);
	}

	/**
	 * @param string $label
	 * @return Column
	 */
	public function setLabel(string $label): Column
	{
		$this->label = $label;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSortable(): bool
	{
		return $this->sortable;
	}

	/**
	 * @param bool $isSortable
	 * @return Column
	 */
	public function setSortable(bool $isSortable): Column
	{
		$this->sortable = $isSortable;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isFake(): bool
	{
		return $this->fake;
	}

	/**
	 * @param bool $fake
	 * @return Column
	 */
	public function setFake(bool $fake): Column
	{
		$this->fake = $fake;
		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getValueGetter()
	{
		return $this->valueGetter;
	}

	/**
	 * @param callable $valueGetter
	 * @return Column
	 */
	public function setValueGetter($valueGetter): Column
	{
		$this->valueGetter = $valueGetter;
		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getValueFormatter()
	{
		return $this->valueFormatter;
	}

	/**
	 * @param callable|null $valueFormatter
	 * @return Column
	 */
	public function setValueFormatter($valueFormatter): Column
	{
		$this->valueFormatter = $valueFormatter;
		return $this;
	}

	/**
	 * @return callable|null
	 */
	public function getShouldDisplayCallback()
	{
		return $this->shouldDisplayCallback;
	}

	/**
	 * @param callable|null $shouldDisplayCallback
	 * @return Column
	 */
	public function setShouldDisplayCallback($shouldDisplayCallback): Column
	{
		$this->shouldDisplayCallback = $shouldDisplayCallback;
		return $this;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasExtraConfigPart(string $key): bool
	{
		return isset($this->extraConfig[$key]);
	}

	/**
	 * @param string     $key
	 * @param mixed|null $default
	 * @return mixed|null
	 */
	public function getExtraConfigPart(string $key, $default = null)
	{
		return $this->extraConfig[$key] ?? $default;
	}

	/**
	 * @return array
	 */
	public function getExtraConfig(): array
	{
		return $this->extraConfig;
	}

	/**
	 * @param string $key
	 * @param        $value
	 * @return Column
	 */
	public function setExtraConfigPart(string $key, $value): Column
	{
		$this->extraConfig[$key] = $value;
		return $this;
	}

	/**
	 * @param $source
	 * @return mixed|null
	 */
	public function getValue($source)
	{
		if ($this->valueGetter === null) return null;

		$value = call_user_func($this->valueGetter, $source);

		return $this->valueFormatter ? call_user_func($this->valueFormatter, $value) : $value;
	}
}