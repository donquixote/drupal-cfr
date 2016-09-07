<?php

namespace Drupal\cfrreflection\CfrGen\ParamToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface;

class ParamToConfigurator implements ParamToConfiguratorInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  private $interfaceToConfigurator;

  /**
   * @param \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $interfaceToConfigurator
   */
  public function __construct(TypeToConfiguratorInterface $interfaceToConfigurator) {
    $this->interfaceToConfigurator = $interfaceToConfigurator;
  }

  /**
   * @param \ReflectionParameter $param
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|mixed
   */
  public function paramGetConfigurator(\ReflectionParameter $param, CfrContextInterface $context = NULL) {
    $typeHintReflectionClassLike = $param->getClass();
    if (!$typeHintReflectionClassLike) {
      return NULL;
    }
    return !$param->isOptional()
      ? $this->interfaceToConfigurator->typeGetConfigurator($typeHintReflectionClassLike->getName(), $context)
      : $this->interfaceToConfigurator->typeGetOptionalConfigurator($typeHintReflectionClassLike->getName(), $context, $param->getDefaultValue());
  }
}
