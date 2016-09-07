<?php

namespace Drupal\cfrapi\SometimesConfigurable;

interface PossiblyOptionlessInterface {

  /**
   * @return bool
   */
  public function isOptionless();

}
