<?php

namespace Donquixote\cf\Schema\Neutral;

use Donquixote\cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

class CfSchema_Neutral_IfaceTransformed extends CfSchema_NeutralBase {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   */
  public function __construct(
    CfSchemaInterface $decorated,
    $interface,
    CfrContextInterface $context = NULL
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
   * @return \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  public function getContext() {
    return $this->context;
  }

}
