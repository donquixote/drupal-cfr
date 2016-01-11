<?php

namespace Drupal\cfrapi\SometimesConfigurable;

interface PossiblyUnconfigurableInterface {

  /**
   * @return bool
   */
  function isConfigurable();

}
