<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Context\CfContextInterface;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_InlineExpanded;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface;

class TypeToSchema_InlineExpanded implements TypeToSchemaInterface {

  /**
   * @var \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @param \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $decorated
   * @param \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   */
  public function __construct(
    TypeToSchemaInterface $decorated,
    TypeToDefmapInterface $typeToDefmap
  ) {
    $this->decorated = $decorated;
    $this->typeToDefmap = $typeToDefmap;
  }

  /**
   * @param string $type
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($type, CfContextInterface $context = NULL) {

    $schema = $this->decorated->typeGetCfrSchema($type, $context);

    if ($schema instanceof CfSchema_DrilldownInterface) {
      $schema = new CfSchema_Drilldown_InlineExpanded(
        $schema,
        $this->typeToDefmap->typeGetDefmap($type));
    }

    return $schema;
  }
}
