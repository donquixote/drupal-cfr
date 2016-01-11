<?php

namespace Drupal\cfrplugindiscovery\Annotation\Resolver;

use vektah\parser_combinator\language\php\annotation\DoctrineAnnotation;

interface AnnotationResolverInterface {

  /**
   * @param DoctrineAnnotation $annotation
   *
   * @return mixed|null
   */
  function resolve(DoctrineAnnotation $annotation);

} 
