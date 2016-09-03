<?php

namespace Drupal\cfrreflection\ValueToCallback;

use Donquixote\CallbackReflection\Util\CallbackUtil;

class CallableToCallback implements ValueToCallbackInterface {

  /**
   * @param mixed $callback
   *
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function valueGetCallback($callback) {
    return CallbackUtil::callableGetCallback($callback);
  }
}
