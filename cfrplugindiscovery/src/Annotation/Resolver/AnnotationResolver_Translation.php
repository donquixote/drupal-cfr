<?php

namespace Drupal\cfrplugindiscovery\Annotation\Resolver;

use vektah\parser_combinator\language\php\annotation\DoctrineAnnotation;

/**
 * Resolver for nested '@Translation(..)' annotations.
 */
class AnnotationResolver_Translation implements AnnotationResolverInterface {

  /**
   * @param DoctrineAnnotation $annotation
   *
   * @return mixed|null
   */
  function resolve(DoctrineAnnotation $annotation) {
    if (!isset($annotation->arguments['value'])) {
      return NULL;
    }
    $value = $annotation->arguments['value'];
    if (is_string($value)) {
      return t($value);
    }
    return NULL;
  }
}
