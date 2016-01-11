<?php
namespace Drupal\cfrplugindiscovery\Annotation\Arguments;

interface AnnotationArgumentsResolverInterface {

  /**
   * @param array $args
   *
   * @return array
   */
  function resolveAnnotationArguments(array $args);
}
