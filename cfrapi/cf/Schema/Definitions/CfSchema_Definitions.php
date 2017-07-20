<?php

namespace Donquixote\Cf\Schema\Definitions;

use Donquixote\Cf\Context\CfContextInterface;

class CfSchema_Definitions implements CfSchema_DefinitionsInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param array[] $definitions
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(array $definitions, CfContextInterface $context = NULL) {
    $this->definitions = $definitions;
    $this->context = $context;
  }

  /**
   * @return array
   */
  public function getDefinitions() {
    return $this->definitions;
  }

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext() {
    return $this->context;
  }
}
