<?php

namespace Drupal\cfrplugindiscovery\DocToAnnotations;


use Donquixote\HastyReflectionCommon\NamespaceUseContext\NamespaceUseContextInterface;

interface DocToAnnotationsInterface {

  /**
   * @param string|null $docComment
   * @param \Donquixote\HastyReflectionCommon\NamespaceUseContext\NamespaceUseContextInterface $context
   *
   * @return array[]
   */
  function docGetAnnotations($docComment, NamespaceUseContextInterface $context);

}
