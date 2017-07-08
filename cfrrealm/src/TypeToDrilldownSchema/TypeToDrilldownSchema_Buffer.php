<?php

namespace Drupal\cfrrealm\TypeToDrilldownSchema;

use Drupal\cfrapi\Context\CfrContextInterface;

class TypeToDrilldownSchema_Buffer implements TypeToDrilldownSchemaInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDrilldownSchema\TypeToDrilldownSchemaInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface[]
   */
  private $schemas = [];

  /**
   * @param \Drupal\cfrrealm\TypeToDrilldownSchema\TypeToDrilldownSchemaInterface $decorated
   */
  public function __construct(TypeToDrilldownSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface|\Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDrilldownSchema($type, CfrContextInterface $context = NULL) {
    return array_key_exists($type, $this->schemas)
      ? $this->schemas[$type]
      : $this->schemas[$type] = $this->decorated->typeGetDrilldownSchema($type, $context);
  }

}
