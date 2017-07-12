<?php

namespace Donquixote\Cf\Schema\Defmap;

use Donquixote\Cf\Context\CfContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;

class CfSchema_Defmap implements CfSchema_DefmapInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL) {
    $this->definitionMap = $definitionMap;
    $this->context = $context;
  }

  /**
   * @return \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  public function getDefinitionMap() {
    return $this->definitionMap;
  }

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext() {
    return $this->context;
  }
}
