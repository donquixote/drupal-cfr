<?php

namespace Drupal\cfrapi\CfrSchema\Callback;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface CallbackSchemaInterface extends CfrSchemaInterface {

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback();

}
