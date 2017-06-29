<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator\Partial;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchema\Iface\IfaceSchemaInterface;
use Drupal\cfrapi\CfrSchema\ValueToValue\ValueToValueSchemaInterface;
use Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface;
use Drupal\cfrapi\CfrSchema\Group\GroupSchemaInterface;
use Drupal\cfrapi\CfrSchema\Options\OptionsSchemaInterface;
use Drupal\cfrapi\CfrSchema\Sequence\SequenceSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrapi\Configurator\Configurator_DrilldownSchema;
use Drupal\cfrapi\Configurator\Configurator_ValueToValue;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Group\Configurator_GroupSchema;
use Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect;
use Drupal\cfrapi\Configurator\Sequence\Configurator_Sequence;

class CfrSchemaToConfiguratorPartial_Hardcoded implements CfrSchemaToConfiguratorPartialInterface {



  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   */
  public function cfrSchemaGetConfigurator(
    CfrSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    /*
    if ($cfrSchema instanceof OptionalSchemaInterface) {
      $requiredCfrSchema = $cfrSchema->getCfrSchema();
      $requiredConfigurator = $this->cfrSchemaGetConfigurator($requiredCfrSchema);
      if ($requiredConfigurator instanceof Optionable
      return new Configurator_LegendSelect(
        $cfrSchema);
    }
    /**/

    if ($cfrSchema instanceof ConfiguratorInterface) {
      return $cfrSchema;
    }

    if ($cfrSchema instanceof ValueToValueSchemaInterface) {
      $decoratedSchema = $cfrSchema->getDecorated();
      $decoratedConfigurator = $cfrSchemaToConfigurator->cfrSchemaGetConfigurator($decoratedSchema);
      return new Configurator_ValueToValue($decoratedConfigurator, $cfrSchema);
    }

    if ($cfrSchema instanceof DrilldownSchemaInterface) {
      return new Configurator_DrilldownSchema(
        $cfrSchema,
        $cfrSchemaToConfigurator);
    }

    if ($cfrSchema instanceof OptionsSchemaInterface) {
      return new Configurator_LegendSelect($cfrSchema);
    }

    if ($cfrSchema instanceof GroupSchemaInterface) {
      return new Configurator_GroupSchema(
        $cfrSchema,
        $cfrSchemaToConfigurator);
    }

    if ($cfrSchema instanceof SequenceSchemaInterface) {
      $itemSchema = $cfrSchema->getItemSchema();
      $itemConfigurator = $cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator($itemSchema);
      // @todo What if there is no such configurator?
      return new Configurator_Sequence($itemConfigurator);
    }

    if ($cfrSchema instanceof IfaceSchemaInterface) {

    }

    // Not supported.
    return FALSE;
  }
}
