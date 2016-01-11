<?php

namespace Drupal\cfrreflection\ValueToCallback;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;

class ClassNameToCallback implements ValueToCallbackInterface {

  /**
   * @param mixed $class
   *
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  function valueGetCallback($class) {
    return CallbackReflection_ClassConstruction::createFromClassNameCandidate($class);
  }
}
