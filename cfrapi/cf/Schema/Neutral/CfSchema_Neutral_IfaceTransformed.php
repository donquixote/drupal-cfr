<?php

namespace Donquixote\Cf\Schema\Neutral;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

class CfSchema_Neutral_IfaceTransformed extends CfSchema_NeutralBase {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    CfSchemaInterface $decorated,
    $interface,
    CfContextInterface $context = NULL
  ) {
    parent::__construct($decorated);
    $this->interface = $interface;
    $this->context = $context;
  }

  /**
   * @return string
   */
  public function getInterface() {
    return $this->interface;
  }

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext() {
    return $this->context;
  }

}
