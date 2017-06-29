<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator;

use Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface;
use Drupal\cfrapi\Configurator\Configurator_DrilldownSchema;
use Drupal\cfrapi\Util\UtilBase;

final class CfrSchemaToConfiguratorUtil extends UtilBase {

  public static function drilldown(
    DrilldownSchemaInterface $drilldownSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    return new Configurator_DrilldownSchema($drilldownSchema, $cfrSchemaToConfigurator);
  }

}
