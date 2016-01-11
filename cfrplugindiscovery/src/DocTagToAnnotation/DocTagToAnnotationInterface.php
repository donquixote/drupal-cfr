<?php

namespace Drupal\cfrplugindiscovery\DocTagToAnnotation;

use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;

interface DocTagToAnnotationInterface {

  /**
   * @param \phpDocumentor\Reflection\DocBlock\Tags\BaseTag $docTag
   *
   * @return \vektah\parser_combinator\language\php\annotation\DoctrineAnnotation|null
   */
  function docTagGetAnnotation(BaseTag $docTag);

}
