<?php

namespace Drupal\cfrapi\ValueProvider;

use Drupal\cfrapi\PhpProvider\PhpProviderInterface;

interface ValueProviderInterface extends PhpProviderInterface {

  /**
   * @return mixed
   */
  public function getValue();
}
