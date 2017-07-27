<?php

namespace Donquixote\Cf\Discovery\AnnotatedFactory;

interface AnnotatedFactoryInterface {

  /**
   * @param string $prefix
   *
   * @return array|null
   */
  public function createDefinition($prefix);

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback();

  /**
   * @return string
   */
  public function getDocComment();

  /**
   * @return string[]
   */
  public function getReturnTypeNames();
}
