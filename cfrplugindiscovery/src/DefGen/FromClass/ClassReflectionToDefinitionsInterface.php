<?php

namespace Drupal\cfrplugindiscovery\DefGen\FromClass;

use Donquixote\HastyReflectionCommon\Reflection\ClassLike\ClassLikeReflectionInterface;

interface ClassReflectionToDefinitionsInterface {

  /**
   * @param \Donquixote\HastyReflectionCommon\Reflection\ClassLike\ClassLikeReflectionInterface $classLikeReflection
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  function classReflectionGetDefinitions(ClassLikeReflectionInterface $classLikeReflection);

}
