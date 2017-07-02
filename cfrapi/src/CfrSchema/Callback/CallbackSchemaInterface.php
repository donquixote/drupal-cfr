<?php

namespace Drupal\cfrapi\CfrSchema\Callback;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface CallbackSchemaInterface extends CfrSchemaInterface {

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback();

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[]
   */
  public function getExplicitParamSchemas();

  /**
   * @return string[]
   */
  public function getExplicitParamLabels();

  /**
   * @return \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  public function getContext();

}
