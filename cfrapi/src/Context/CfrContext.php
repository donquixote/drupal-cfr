<?php

namespace Drupal\cfrapi\Context;

class CfrContext implements CfrContextInterface {

  /**
   * @var mixed[]
   */
  private $values = array();

  /**
   * @var string|null
   */
  private $machineName;

  /**
   * @return static
   */
  public static function create() {
    return new static();
  }

  /**
   * @param string $paramName
   * @param mixed $value
   *
   * @return $this
   */
  function paramNameSetValue($paramName, $value) {
    $this->values[$paramName] = $value;
    return $this;
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  function paramValueExists(\ReflectionParameter $param) {
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
  function paramGetValue(\ReflectionParameter $param) {
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
  function paramNameHasValue($paramName) {
    return array_key_exists($paramName, $this->values);
  }

  /**
   * @param string $paramName
   *
   * @return mixed|null
   */
  function paramNameGetValue($paramName) {
    return array_key_exists($paramName, $this->values)
      ? $this->values[$paramName]
      : NULL;
  }

  /**
   * @return string
   */
  function getMachineName() {
    return isset($this->machineName)
      ? $this->machineName
      : $this->machineName = md5(serialize($this->values));
  }
}
