<?php

namespace Drupal\cfrrealm\TypeToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;

interface TypeToConfiguratorInterface {

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function typeGetConfigurator($type, CfrContextInterface $context = NULL);

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function typeGetOptionalConfigurator($type, CfrContextInterface $context = NULL, $defaultValue = NULL);

}
