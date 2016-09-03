<?php

namespace Drupal\cfrreflection\CfrGen\InterfaceToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;

interface InterfaceToConfiguratorInterface {

  /**
   * @param string $interface
   *   Qualified class name of an interface.
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function interfaceGetConfigurator($interface, CfrContextInterface $context = NULL);

  /**
   * @param string $interface
   *   Qualified class name of an interface.
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function interfaceGetOptionalConfigurator($interface, CfrContextInterface $context = NULL, $defaultValue = NULL);

}
