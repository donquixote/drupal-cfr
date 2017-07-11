<?php

namespace Donquixote\Cf\Discovery\AnnotatedFactoryIA;

use Donquixote\Cf\Discovery\AnnotatedFactory;
use Donquixote\Cf\Discovery\ClassFilesIAInterface;
use Donquixote\Cf\Util\DiscoveryUtil;

class AnnotatedFactoriesIA implements AnnotatedFactoriesIAInterface {

  /**
   * @var \Donquixote\Cf\Discovery\ClassFilesIAInterface
   */
  private $classFilesIA;

  /**
   * @var string
   */
  private $annotationTagName;

  /**
   * @param \Donquixote\Cf\Discovery\ClassFilesIAInterface $classFilesIA
   * @param string $annotationTagName
   */
  public function __construct(ClassFilesIAInterface $classFilesIA, $annotationTagName) {
    $this->classFilesIA = $classFilesIA;
    $this->annotationTagName = $annotationTagName;
  }

  /**
   * @return \Iterator|\Donquixote\Cf\Discovery\AnnotatedFactory[]
   */
  public function getIterator() {

    foreach (DiscoveryUtil::findReflectionClasses($this->classFilesIA) as $reflClass) {
      // "yield from" is not supported in PHP 5.6.
      foreach ($this->reflectionClassGetFactories($reflClass) as $factory) {
        yield $factory;
      }
    }
  }

  /**
   * @param \ReflectionClass $reflClass
   *
   * @return \Iterator|\Donquixote\Cf\Discovery\AnnotatedFactory[]
   */
  private function reflectionClassGetFactories(\ReflectionClass $reflClass) {

    if (1
      && $reflClass->isInstantiable()
      && FALSE !== ($classDoc = $reflClass->getDocComment())
      && FALSE !== strpos($classDoc, '@' . $this->annotationTagName)
    ) {
      yield AnnotatedFactory::createFromClass($reflClass);
    }

    foreach ($reflClass->getMethods() as $reflectionMethod) {
      if (1
        && $reflectionMethod->isStatic()
        && FALSE !== ($methodDoc = $reflectionMethod->getDocComment())
        && FALSE !== strpos($methodDoc, '@' . $this->annotationTagName)
      ) {
        yield AnnotatedFactory::createFromStaticMethod($reflectionMethod);
      }
    }
  }

}
