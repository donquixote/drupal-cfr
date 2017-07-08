<?php

namespace Drupal\cfrfamily\DrilldownSchema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface;

class CfSchema_Drilldown_InlineExpanded extends CfSchema_Drilldown_InlineExpandedBase {

  /**
   * @var \Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   * @param \Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface $idToDefinition
   */
  public function __construct(CfSchema_DrilldownInterface $decorated, IdToDefinitionInterface $idToDefinition) {
    parent::__construct($decorated);
    $this->idToDefinition = $idToDefinition;
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  protected function idIsInlined($id) {

    if (NULL !== $definition = $this->idToDefinition->idGetDefinition($id)) {
      return !empty($definition['inline']);
    }

    return FALSE;
  }
}
