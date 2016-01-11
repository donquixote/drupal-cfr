<?php

namespace Drupal\cfrreflection\ParamToLabel;

/**
 * A service to auto-generate a label from a reflection parameter.
 */
interface ParamToLabelInterface {

  /**
   * @param \ReflectionParameter $param
   *
   * @return string|null
   */
  function paramGetLabel(\ReflectionParameter $param);

}
