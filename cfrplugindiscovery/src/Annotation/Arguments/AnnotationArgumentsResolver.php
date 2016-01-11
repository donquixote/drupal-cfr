<?php


namespace Drupal\cfrplugindiscovery\Annotation\Arguments;

use Drupal\cfrplugindiscovery\Annotation\Constant\ConstantResolver;
use Drupal\cfrplugindiscovery\Annotation\Constant\ConstantResolverInterface;
use Drupal\cfrplugindiscovery\Annotation\Resolver\AnnotationResolver_Translation;
use Drupal\cfrplugindiscovery\Annotation\Resolver\AnnotationResolverInterface;
use vektah\parser_combinator\language\php\annotation\ConstLookup;
use vektah\parser_combinator\language\php\annotation\DoctrineAnnotation;

class AnnotationArgumentsResolver implements AnnotationArgumentsResolverInterface {

  /**
   * @var AnnotationResolverInterface[]
   */
  private $annotationResolvers;

  /**
   * @var ConstantResolverInterface
   */
  private $constantResolver;

  /**
   * @return \Drupal\cfrplugindiscovery\Annotation\Arguments\AnnotationArgumentsResolver
   */
  static function create() {
    return new self(
      array(
        't' => $t = new AnnotationResolver_Translation(),
        'Translate' => $t,
      ),
      ConstantResolver::create()
    );
  }

  /**
   * @param AnnotationResolverInterface[] $annotationResolvers
   * @param ConstantResolverInterface $constantResolver
   */
  function __construct(array $annotationResolvers, ConstantResolverInterface $constantResolver) {
    $this->annotationResolvers = $annotationResolvers;
    $this->constantResolver = $constantResolver;
  }

  /**
   * @param array $args
   *
   * @return array
   */
  function resolveAnnotationArguments(array $args) {
    foreach ($args as &$arg) {
      if ($arg instanceof DoctrineAnnotation) {
        $arg = $this->resolveNestedAnnotation($arg);
      }
      elseif ($arg instanceof ConstLookup) {
        $arg = $this->constantResolver->resolveConstant($arg);
      }
      elseif (is_array($arg)) {
        // Resolve arguments of sub-array.
        $arg = $this->resolveAnnotationArguments($arg);
      }
    }
    return $args;
  }

  /**
   * @param DoctrineAnnotation $nestedAnnotation
   *
   * @return null|mixed
   */
  private function resolveNestedAnnotation($nestedAnnotation) {
    if (isset($this->annotationResolvers[$nestedAnnotation->name])) {
      return $this->annotationResolvers[$nestedAnnotation->name]->resolve($nestedAnnotation);
    }
    return NULL;
  }

} 
