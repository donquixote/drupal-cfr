<?php

namespace Drupal\cfrrealm\TypeToCfrSchema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_InlineExpanded;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

class TypeToCfrSchema_InlineExpanded implements TypeToCfrSchemaInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @param \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface $decorated
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   */
  public function __construct(
    TypeToCfrSchemaInterface $decorated,
    TypeToDefmapInterface $typeToDefmap
  ) {
    $this->decorated = $decorated;
    $this->typeToDefmap = $typeToDefmap;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($type, CfrContextInterface $context = NULL) {

    $schema = $this->decorated->typeGetCfrSchema($type, $context);

    if ($schema instanceof CfSchema_DrilldownInterface) {
      $schema = new CfSchema_Drilldown_InlineExpanded(
        $schema,
        $this->typeToDefmap->typeGetDefmap($type));
    }

    return $schema;
  }
}
