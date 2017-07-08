<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Drupal\cfrapi\Configurator\Configurator_DrilldownSchema;
use Drupal\cfrapi\Util\UtilBase;

final class CfrSchemaToConfiguratorUtil extends UtilBase {

  public static function drilldown(
    CfSchema_DrilldownInterface $drilldownSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    return new Configurator_DrilldownSchema($drilldownSchema, $cfrSchemaToConfigurator);
  }

}
