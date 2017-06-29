<?php

namespace Drupal\cfrapi\CfrSchema\Iface;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface IfaceSchemaInterface extends CfrSchemaInterface {

  /**
   * @return string
   */
  public function getInterface();

  /**
   * @return \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  public function getContext();

}
