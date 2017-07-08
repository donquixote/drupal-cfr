<?php

namespace Drupal\cfrrealm\TypeToDrilldownSchema;

use Drupal\cfrapi\Context\CfrContextInterface;

interface TypeToDrilldownSchemaInterface {

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function typeGetDrilldownSchema($type, CfrContextInterface $context = NULL);

}
