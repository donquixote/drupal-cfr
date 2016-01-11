<?php

namespace Drupal\cfrplugindiscovery\DocToReturnTypes;

use Donquixote\HastyReflectionCommon\NamespaceUseContext\NamespaceUseContextInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

/**
 * Uses phpDocumentor to extract "@return" types from a docblock.
 */
class DocToReturnTypes_phpDocumentor implements DocToReturnTypesInterface {

  /**
   * @var \phpDocumentor\Reflection\DocBlockFactoryInterface
   */
  private $docBlockFactory;

  /**
   * @return \Drupal\cfrplugindiscovery\DocToReturnTypes\DocToReturnTypes_phpDocumentor
   */
  static function create() {
    return new self(
      DocBlockFactory::createInstance()
    );
  }

  /**
   * @param \phpDocumentor\Reflection\DocBlockFactoryInterface $docBlockFactory
   */
  function __construct(DocBlockFactoryInterface $docBlockFactory) {
    $this->docBlockFactory = $docBlockFactory;
  }

  /**
   * @param string $docComment
   * @param \Donquixote\HastyReflectionCommon\NamespaceUseContext\NamespaceUseContextInterface $context
   *
   * @return string[]
   *   Format: $[$qcn] = $qcn
   */
  function docGetReturnTypes($docComment, NamespaceUseContextInterface $context) {
    $docblock = $this->docBlockFactory->create($docComment);
    $returnTags = $docblock->getTagsByName('return');
    $returnTypes = array();
    foreach ($returnTags as $tag) {
      if ($tag instanceof Return_) {
        $returnTagTypesStr = $tag->getType();
        foreach (explode('|', $returnTagTypesStr) as $typeNameOrAlias) {
          $name = $context->aliasGetName($typeNameOrAlias);
          $returnTypes[$name] = $name;
        }
      }
    }
    return $returnTypes;
  }
}
