<?php

namespace Drupal\cfrplugindiscovery\DefGen\FromStaticMethod;

use Donquixote\HastyReflectionCommon\Reflection\FunctionLike\FunctionLikeReflectionInterface;

interface FunctionLikeReflectionToDefinitionsInterface {

  /**
   * @param \Donquixote\HastyReflectionCommon\Reflection\FunctionLike\FunctionLikeReflectionInterface $method
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  function functionReflectionGetDefinitions(FunctionLikeReflectionInterface $method);

}
