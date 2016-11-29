<?php

namespace Drupal\cfrapi\CfrGroupSchema;

abstract class CfrGroupSchema_DecoratorBase implements CfrGroupSchemaInterface {

  /**
   * @var \Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface
   */
  private $decorated;

  /**
   * The constructor.
   *
   * @param \Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface $decorated
   */
  public function __construct(CfrGroupSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  public function getConfigurators() {
    return $this->decorated->getConfigurators();
  }

  /**
   * @return string[]
   */
  public function getLabels() {
    return $this->decorated->getLabels();
  }

  /**
   * @param mixed[] $values
   *   Values returned from group configurators.
   *
   * @return mixed
   */
  public function valuesGetValue(array $values) {
    return $this->decorated->valuesGetValue($values);
  }
}
