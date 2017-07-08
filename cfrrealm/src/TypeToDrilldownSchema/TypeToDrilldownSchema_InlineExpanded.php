<?php

namespace Drupal\cfrrealm\TypeToDrilldownSchema;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_InlineExpanded;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

class TypeToDrilldownSchema_InlineExpanded implements TypeToDrilldownSchemaInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDrilldownSchema\TypeToDrilldownSchemaInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @param \Drupal\cfrrealm\TypeToDrilldownSchema\TypeToDrilldownSchemaInterface $decorated
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   */
  public function __construct(
    TypeToDrilldownSchemaInterface $decorated,
    TypeToDefmapInterface $typeToDefmap
  ) {
    $this->decorated = $decorated;
    $this->typeToDefmap = $typeToDefmap;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function typeGetDrilldownSchema($type, CfrContextInterface $context = NULL) {

    return new CfSchema_Drilldown_InlineExpanded(
      $this->decorated->typeGetDrilldownSchema($type, $context),
      $this->typeToDefmap->typeGetDefmap($type));
  }
}
