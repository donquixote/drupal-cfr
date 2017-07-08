<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;

class CfrSchemaToConfigurator_Proxy implements CfrSchemaToConfiguratorInterface {

  /**
   * @var callable
   */
  private $factory;

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface|null
   */
  private $instance;

  /**
   * @param callable $factory
   */
  public function __construct($factory) {
    $this->factory = $factory;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $cfrSchema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetConfigurator(CfSchemaInterface $cfrSchema) {

    if (NULL === $this->instance) {
      $this->instance = call_user_func($this->factory);
    }

    return $this->instance->cfrSchemaGetConfigurator($cfrSchema);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $cfrSchema
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetOptionalConfigurator(CfSchemaInterface $cfrSchema) {

    if (NULL === $this->instance) {
      $this->instance = call_user_func($this->factory);
    }

    return $this->instance->cfrSchemaGetOptionalConfigurator($cfrSchema);
  }
}
