<?php

namespace Drupal\cfrreflection\CfrGen\ParamToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface;

class ParamToConfigurator implements ParamToConfiguratorInterface {

  /**
   * @var \Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface
   */
  private $interfaceToConfigurator;

  /**
   * @param \Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface $interfaceToConfigurator
   */
  function __construct(InterfaceToConfiguratorInterface $interfaceToConfigurator) {
    $this->interfaceToConfigurator = $interfaceToConfigurator;
  }

  /**
   * @param \ReflectionParameter $param
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|mixed
   */
  function paramGetConfigurator(\ReflectionParameter $param, CfrContextInterface $context = NULL) {
    $typeHintReflectionClassLike = $param->getClass();
    if (!$typeHintReflectionClassLike) {
      return NULL;
    }
    return !$param->isOptional()
      ? $this->interfaceToConfigurator->interfaceGetConfigurator($typeHintReflectionClassLike->getName(), $context)
      : $this->interfaceToConfigurator->interfaceGetOptionalConfigurator($typeHintReflectionClassLike->getName(), $context, $param->getDefaultValue());
  }
}
