<?php

namespace Drupal\cfrplugindiscovery\DocToReturnTypes;


use Donquixote\HastyReflectionCommon\NamespaceUseContext\NamespaceUseContextInterface;

interface DocToReturnTypesInterface {

  /**
   * @param string $docComment
   * @param \Donquixote\HastyReflectionCommon\NamespaceUseContext\NamespaceUseContextInterface $context
   *
   * @return string[]
   *   Format: $[$qcn] = $qcn
   */
  function docGetReturnTypes($docComment, NamespaceUseContextInterface $context);

}
