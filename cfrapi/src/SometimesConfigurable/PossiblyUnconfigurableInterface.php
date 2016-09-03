<?php

namespace Drupal\cfrapi\SometimesConfigurable;

interface PossiblyUnconfigurableInterface {

  /**
   * @return bool
   */
  public function isConfigurable();

}
