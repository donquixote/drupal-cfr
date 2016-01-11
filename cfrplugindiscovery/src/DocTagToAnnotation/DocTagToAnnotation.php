<?php

namespace Drupal\cfrplugindiscovery\DocTagToAnnotation;

use Drupal\cfrplugindiscovery\Annotation\Arguments\AnnotationArgumentsResolver;
use Drupal\cfrplugindiscovery\Annotation\Arguments\AnnotationArgumentsResolverInterface;
use Drupal\cfrplugindiscovery\Annotation\Grammar\Grammar_DoctrineLikeAnnotation;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use vektah\parser_combinator\Input;
use vektah\parser_combinator\language\php\annotation\DoctrineAnnotation;
use vektah\parser_combinator\parser\Parser;

class DocTagToAnnotation implements DocTagToAnnotationInterface {

  /**
   * @var \Drupal\cfrplugindiscovery\Annotation\Arguments\AnnotationArgumentsResolverInterface
   */
  private $argumentsResolver;

  /**
   * @var \vektah\parser_combinator\parser\Parser
   */
  private $annotationParser;

  /**
   * @return \Drupal\cfrplugindiscovery\DocTagToAnnotation\DocTagToAnnotation
   */
  static function create() {
    return new self(
      (new Grammar_DoctrineLikeAnnotation)->doctrine_annotation,
      AnnotationArgumentsResolver::create()
    );
  }

  /**
   * @param \vektah\parser_combinator\parser\Parser $annotationParser
   * @param \Drupal\cfrplugindiscovery\Annotation\Arguments\AnnotationArgumentsResolverInterface $argumentsResolver
   */
  function __construct(
    Parser $annotationParser,
    AnnotationArgumentsResolverInterface $argumentsResolver
  ) {
    $this->annotationParser = $annotationParser;
    $this->argumentsResolver = $argumentsResolver;
  }

  /**
   * @param \phpDocumentor\Reflection\DocBlock\Tags\BaseTag $docTag
   *
   * @return array|null
   */
  function docTagGetAnnotation(BaseTag $docTag) {
    // The parser requires a complete tag, not just the tag body.
    // Since we already know the tag name, we intionally put a placeholder tag
    // name here.
    $tagStr = '@_' . trim($docTag->getDescription());
    $result = $this->annotationParser->parse(new Input($tagStr));
    if (empty($result->data)) {
      return NULL;
    }
    $annotation = $result->data;
    if (!$annotation instanceof DoctrineAnnotation) {
      return NULL;
    }
    return $this->argumentsResolver->resolveAnnotationArguments($annotation->arguments);
  }

}
