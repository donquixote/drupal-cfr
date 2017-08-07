<?php

namespace Donquixote\Cf\Discovery\DocToAnnotations;

use Donquixote\Annotation\DocCommentUtil;
use Donquixote\Annotation\Resolver\AnnotationResolver_PrimitiveResolver;
use Donquixote\Annotation\Resolver\AnnotationResolverInterface;
use Donquixote\Annotation\Value\DoctrineAnnotation\DoctrineAnnotationInterface;
use Drupal\Component\Render\MarkupInterface;

class DocToAnnotations implements DocToAnnotationsInterface {

  /**
   * @var string
   */
  private $tagName;

  /**
   * @var \Donquixote\Annotation\Resolver\AnnotationResolverInterface
   */
  private $annotationResolver;

  /**
   * @param string $tagName;
   *
   * @return self
   */
  public static function create($tagName) {
    return new self($tagName, AnnotationResolver_PrimitiveResolver::create());
  }

  /**
   * @param string $tagName
   * @param \Donquixote\Annotation\Resolver\AnnotationResolverInterface $annotationResolver
   */
  public function __construct($tagName, AnnotationResolverInterface $annotationResolver) {
    $this->tagName = $tagName;
    $this->annotationResolver = $annotationResolver;
  }

  /**
   * @param string|null $docComment
   *
   * @return array[]
   */
  public function docGetAnnotations($docComment) {

    if (false === strpos($docComment, '@' . $this->tagName)) {
      return [];
    }

    $annotations = [];
    foreach (DocCommentUtil::docGetDoctrineAnnotations($docComment, $this->tagName, $this->annotationResolver) as $doctrineAnnotation) {
      $annotation = [];
      foreach ($doctrineAnnotation->getArguments() as $k => $v) {
        if ($v instanceof DoctrineAnnotationInterface) {
          if (null === $v = $this->resolveAnnotation($v->getName(), $v->getArguments())) {
            continue;
          }
        }
        elseif (is_object($v)) {
          continue;
        }
        $annotation[$k] = $v;
      }
      $annotations[] = $annotation;
    }

    return $annotations;
  }

  /**
   * @param string $name
   * @param array $args
   *
   * @return mixed|null
   */
  private function resolveAnnotation($name, array $args) {

    if ($name === 't' || $name === 'Translate') {
      if (isset($args[0]) && is_string($args[0])) {
        $translated = t($args[0]);
        if ($translated instanceof MarkupInterface) {
          return $translated->render();
        }
        if (is_string($translated)) {
          return $translated;
        }
        return $args[0];
      }
    }

    return NULL;

  }
}
