<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrfamily\Configurator\Composite\Configurator_IdConfBase;
use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;

class Configurator_DrilldownSchema extends Configurator_IdConfBase {

  /**
   * @var \Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface
   */
  private $drilldownSchema;

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   */
  private $cfrSchemaToConfigurator;

  /**
   * @param \Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface $drilldownSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   * @param bool $required
   */
  public function __construct(
    DrilldownSchemaInterface $drilldownSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator,
    $required = TRUE
  ) {
    $this->drilldownSchema = $drilldownSchema;
    $this->cfrSchemaToConfigurator = $cfrSchemaToConfigurator;

    parent::__construct(
      $required,
      ($drilldownSchema instanceof IdValueToValueInterface)
        ? $drilldownSchema
        : NULL);
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {
    return $this->drilldownSchema->getSelectOptions();
  }

  /**
   * @param string $id
   *
   * @return string
   */
  protected function idGetLabel($id) {
    return $this->drilldownSchema->idGetLabel($id);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  protected function idGetConfigurator($id) {

    // @todo Cache this!
    if (NULL === $cfrSchema = $this->drilldownSchema->idGetCfrSchema($id)) {
      return NULL;
    }

    if (FALSE === $configurator = $this->cfrSchemaToConfigurator
        ->cfrSchemaGetConfigurator($cfrSchema)
    ) {
      // @todo Throw an exception instead?
      return NULL;
    }

    return $configurator;
  }
}
