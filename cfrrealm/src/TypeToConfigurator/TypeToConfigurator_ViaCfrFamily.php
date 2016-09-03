<?php

namespace Drupal\cfrrealm\TypeToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamilyInterface;

class TypeToConfigurator_ViaCfrFamily implements TypeToConfiguratorInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamilyInterface
   */
  private $typeToCfrFamily;

  /**
   * @param \Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamilyInterface $typeToCfrFamily
   */
  public function __construct(TypeToCfrFamilyInterface $typeToCfrFamily) {
    $this->typeToCfrFamily = $typeToCfrFamily;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function typeGetConfigurator($type, CfrContextInterface $context = NULL) {
    return $this->typeToCfrFamily->typeGetCfrFamily($type, $context)->getFamilyConfigurator();
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function typeGetOptionalConfigurator($type, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    return $this->typeToCfrFamily->typeGetCfrFamily($type, $context)->getOptionalFamilyConfigurator($defaultValue);
  }
}
