<?php

namespace Donquixote\Cf\ParamToValue;

interface ParamToValueInterface {

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  public function paramValueExists(\ReflectionParameter $param);

  /**
   * @param \ReflectionParameter $param
   * @param mixed|null $else
   *
   * @return mixed
   */
  public function paramGetValue(\ReflectionParameter $param, $else = NULL);

  /**
   * @param \ReflectionParameter $param
   *
   * @return string|null
   */
  public function paramGetPhp(\ReflectionParameter $param);

}
