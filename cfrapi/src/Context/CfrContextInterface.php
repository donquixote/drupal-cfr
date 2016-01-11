<?php

namespace Drupal\cfrapi\Context;

interface CfrContextInterface {

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  function paramValueExists(\ReflectionParameter $param);

  /**
   * @param \ReflectionParameter $param
   *
   * @return mixed
   */
  function paramGetValue(\ReflectionParameter $param);

  /**
   * @param string $paramName
   *
   * @return bool
   */
  function paramNameHasValue($paramName);

  /**
   * @param string $paramName
   *
   * @return mixed
   */
  function paramNameGetValue($paramName);

  /**
   * @return string
   */
  function getMachineName();

}
