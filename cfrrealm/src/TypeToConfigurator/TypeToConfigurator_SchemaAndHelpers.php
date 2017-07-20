<?php

namespace Drupal\cfrrealm\TypeToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;

class TypeToConfigurator_SchemaAndHelpers implements TypeToConfiguratorInterface {

  public function __construct() {
  }

  /** @noinspection PhpInconsistentReturnPointsInspection */
  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function typeGetConfigurator($type, CfrContextInterface $context = NULL) {

  }

  /** @noinspection PhpInconsistentReturnPointsInspection */
  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function typeGetOptionalConfigurator($type, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    // TODO: Implement typeGetOptionalConfigurator() method.
  }
}
