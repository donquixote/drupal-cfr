<?php

namespace Drupal\cfrapi\SchemaToConfigurator;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Drupal\cfrapi\Configurator\Configurator_DrilldownSchema;
use Drupal\cfrapi\Util\UtilBase;

final class SchemaToConfiguratorUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $drilldownSchema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Configurator_DrilldownSchema
   */
  public static function drilldown(
    CfSchema_DrilldownInterface $drilldownSchema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    return new Configurator_DrilldownSchema($drilldownSchema, $schemaToConfigurator);
  }

}
