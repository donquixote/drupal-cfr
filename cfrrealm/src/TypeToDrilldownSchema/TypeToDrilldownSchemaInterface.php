<?php

namespace Drupal\cfrrealm\TypeToDrilldownSchema;

use Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

interface TypeToDrilldownSchemaInterface {

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface
   */
  public function typeGetDrilldownSchema($type, CfrContextInterface $context) : DrilldownSchemaInterface;

}
