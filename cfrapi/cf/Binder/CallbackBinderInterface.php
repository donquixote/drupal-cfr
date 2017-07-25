<?php

namespace Donquixote\Cf\Binder;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

interface CallbackBinderInterface {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param true[] $freeParams
   *
   * @return mixed
   */
  public function bind(CallbackReflectionInterface $callback, array $freeParams = []);

}
