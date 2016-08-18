<?php

namespace Drupal\cfrplugindiscovery\DocToAnnotations;

use Drupal\cfrplugindiscovery\DocTagToAnnotation\DocTagToAnnotation;
use Drupal\cfrplugindiscovery\DocTagToAnnotation\DocTagToAnnotationInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;

class DocToAnnotations implements DocToAnnotationsInterface {

  /**
   * @var \phpDocumentor\Reflection\DocBlockFactoryInterface
   */
  private $docBlockFactory;

  /**
   * @var \Drupal\cfrplugindiscovery\DocTagToAnnotation\DocTagToAnnotationInterface
   */
  private $docTagToAnnotation;

  /**
   * @var string
   */
  private $tagName;

  /**
   * @param string $tagName
   *
   * @return \Drupal\cfrplugindiscovery\DocToAnnotations\DocToAnnotations
   */
  static function create($tagName) {
    return new self(
      DocBlockFactory::createInstance(),
      DocTagToAnnotation::create(),
      $tagName
    );
  }

  /**
   * @param \phpDocumentor\Reflection\DocBlockFactoryInterface $docBlockFactory
   * @param \Drupal\cfrplugindiscovery\DocTagToAnnotation\DocTagToAnnotationInterface $docTagToAnnotation
   * @param string $tagName
   */
  function __construct(
    DocBlockFactoryInterface $docBlockFactory,
    DocTagToAnnotationInterface $docTagToAnnotation,
    $tagName
  ) {
    $this->docBlockFactory = $docBlockFactory;
    $this->docTagToAnnotation = $docTagToAnnotation;
    $this->tagName = $tagName;
  }

  /**
   * @param string|null $docComment
   *
   * @return array[]
   */
  function docGetAnnotations($docComment) {

    if (NULL === $docComment) {
      return array();
    }
    if (FALSE === strpos($docComment, '@' . $this->tagName)) {
      return array();
    }

    $docBlock = $this->docBlockFactory->create($docComment);

    $annotations = array();
    foreach ($docBlock->getTagsByName($this->tagName) as $docTag) {
      if (!$docTag instanceof Generic) {
        continue;
      }
      $annotation = $this->docTagToAnnotation->docTagGetAnnotation($docTag);
      if (NULL === $annotation) {
        continue;
      }
      $annotations[] = $annotation;
    }
    return $annotations;
  }
}
