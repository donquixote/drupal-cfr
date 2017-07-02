<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;

class CfrSchemaToConfiguratorPartial_Callback implements CfrSchemaToConfiguratorPartialInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   */
  public function __construct(CallbackReflectionInterface $callbackReflection) {
    $this->callbackReflection = $callbackReflection;
  }

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   */
  public function cfrSchemaGetConfigurator(
    CfrSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    return $this->callbackReflection->invokeArgs([$cfrSchema, $cfrSchemaToConfigurator]);
  }

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|false
   */
  public function cfrSchemaGetOptionalConfigurator(
    CfrSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    return FALSE;
  }
}
