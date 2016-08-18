<?php

namespace Drupal\cfrplugindiscovery\DocToReturnTypesString;

use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

class DocToReturnTypesString_phpDocumentor implements DocToReturnTypesStringInterface {

  /**
   * @var \phpDocumentor\Reflection\DocBlockFactoryInterface
   */
  private $docBlockFactory;

  /**
   * @return DocToReturnTypesString_phpDocumentor
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
   *   The complete doc comment.
   *
   * @return string|null
   *   E.g. '\stdClass|null'
   */
  public function docGetReturnTypesString($docComment) {
    $docblock = $this->docBlockFactory->create($docComment);
    $returnTags = $docblock->getTagsByName('return');
    if (1 !== count($returnTags)) {
      return NULL;
    }
    $tag = reset($returnTags);
    if (!$tag instanceof Return_) {
      return NULL;
    }
    return $tag->getType();
  }
}
