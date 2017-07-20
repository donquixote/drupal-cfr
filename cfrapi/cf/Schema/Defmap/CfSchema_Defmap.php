<?php

namespace Donquixote\Cf\Schema\Defmap;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;

class CfSchema_Defmap implements CfSchema_DefmapInterface {

  /**
   * @var \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL) {
    $this->definitionMap = $definitionMap;
    $this->context = $context;
  }

  /**
   * @return \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
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
