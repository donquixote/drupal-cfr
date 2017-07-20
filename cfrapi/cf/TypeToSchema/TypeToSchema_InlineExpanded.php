<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal_InlineExpanded;
use Donquixote\Cf\Schema\Id\CfSchema_Id_DefmapKey;
use Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface;

class TypeToSchema_InlineExpanded implements TypeToSchemaInterface {

  /**
   * @var \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @param \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $decorated
   * @param \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
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
  public function typeGetSchema($type, CfContextInterface $context = NULL) {

    $schema = $this->decorated->typeGetSchema($type, $context);

    $inlineIdsLookup = new CfSchema_Id_DefmapKey(
      $this->typeToDefmap->typeGetDefmap($type),
      'inline');

    return CfSchema_DrilldownVal_InlineExpanded::createOrSame(
      $schema,
      $inlineIdsLookup);
  }
}
