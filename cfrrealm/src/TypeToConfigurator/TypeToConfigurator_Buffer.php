<?php

namespace Drupal\cfrrealm\TypeToConfigurator;


use Drupal\cfrapi\Context\CfrContextInterface;

class TypeToConfigurator_Buffer implements TypeToConfiguratorInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  private $requiredConfigurators = [];

  /**
   * @var \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface[]
   */
  private $optionalConfigurators = [];

  /**
   * @param \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $decorated
   */
  function __construct(TypeToConfiguratorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|mixed
   */
  function typeGetConfigurator($type, CfrContextInterface $context = NULL) {
    $k = $type;
    if (NULL !== $context) {
      $k .= '::' . $context->getMachineName();
    }
    return array_key_exists($k, $this->requiredConfigurators)
      ? $this->requiredConfigurators[$k]
      : $this->requiredConfigurators[$k] = $this->decorated->typeGetConfigurator($type, $context);
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function typeGetOptionalConfigurator($type, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    $k = $type;
    if (NULL !== $context) {
      $k .= '::' . $context->getMachineName();
    }
    if (NULL !== $defaultValue) {
      $k .= '::' .serialize($defaultValue);
    }
    return array_key_exists($k, $this->optionalConfigurators)
      ? $this->optionalConfigurators[$k]
      : $this->optionalConfigurators[$k] = $this->decorated->typeGetOptionalConfigurator($type, $context);
  }
}
