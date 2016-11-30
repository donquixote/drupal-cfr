<?php

namespace Drupal\cfrapi\Context;

class CfrContext implements CfrContextInterface {

  /**
   * @var mixed[]
   */
  private $values = [];

  /**
   * @var string|null
   */
  private $machineName;

  /**
   * @param mixed[] $values
   *
   * @return static
   */
  public static function create(array $values = []) {
    return new static($values);
  }

  /**
   * @param mixed[] $values
   */
  public function __construct(array $values = []) {
    $this->values = $values;
  }

  /**
   * @param string $paramName
   * @param mixed $value
   *
   * @return $this
   */
  public function paramNameSetValue($paramName, $value) {
    $this->values[$paramName] = $value;
    return $this;
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  public function paramValueExists(\ReflectionParameter $param) {
    if ($typeHintReflClass = $param->getClass()) {
      if ($typeHintReflClass->getName() === CfrContextInterface::class) {
        return TRUE;
      }
    }
    return $this->paramNameHasValue($param->getName());
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return mixed
   */
  public function paramGetValue(\ReflectionParameter $param) {
    if ($typeHintReflClass = $param->getClass()) {
      if ($typeHintReflClass->getName() === CfrContextInterface::class) {
        return $this;
      }
    }
    return $this->paramNameGetValue($param->getName());
  }

  /**
   * @param string $paramName
   *
   * @return bool
   */
  public function paramNameHasValue($paramName) {
    return array_key_exists($paramName, $this->values);
  }

  /**
   * @param string $paramName
   *
   * @return mixed|null
   */
  public function paramNameGetValue($paramName) {
    return array_key_exists($paramName, $this->values)
      ? $this->values[$paramName]
      : NULL;
  }

  /**
   * @return string
   */
  public function getMachineName() {
    return isset($this->machineName)
      ? $this->machineName
      : $this->machineName = md5(serialize($this->values));
  }
}
