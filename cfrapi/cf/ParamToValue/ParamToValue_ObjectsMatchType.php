<?php

namespace Donquixote\Cf\ParamToValue;

class ParamToValue_ObjectsMatchType implements ParamToValueInterface {

  /**
   * @var object[]
   */
  private $objects;

  /**
   * @var object[]
   */
  private $objectsByInterface = [];

  /**
   * @param object[] $objects
   */
  public function __construct(array $objects) {
    $this->objects = $objects;
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  public function paramValueExists(\ReflectionParameter $param) {
    return FALSE !== $this->paramGetObjectOrFalse($param);
  }

  /**
   * @param \ReflectionParameter $param
   * @param mixed|null $else
   *
   * @return mixed
   */
  public function paramGetValue(\ReflectionParameter $param, $else = NULL) {
    return $this->paramGetObjectOrFalse($param) ?: $else;
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return object|false
   */
  private function paramGetObjectOrFalse(\ReflectionParameter $param) {

    if (NULL === $reflectionInterface = $param->getClass()) {
      return FALSE;
    }

    $interface = $reflectionInterface->getName();

    return !isset($this->objectsByInterface[$interface])
      ? $this->objectsByInterface[$interface] = $this->interfaceFindObjectOrFalse(
        $interface)
      : $this->objectsByInterface[$interface];

  }

  /**
   * @param string $interface
   *
   * @return object|false
   */
  private function interfaceFindObjectOrFalse($interface) {

    foreach ($this->objects as $object) {
      if ($object instanceof $interface) {
        return $object;
      }
    }

    return FALSE;
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return string|null
   */
  public function paramGetPhp(\ReflectionParameter $param) {
    // Not supported here.
    return NULL;
  }
}
