<?php

namespace Drupal\cfrrealm\TypeToCfrFamily;

use Drupal\cfrapi\Context\CfrContextInterface;

interface TypeToCfrFamilyInterface {

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  function typeGetCfrFamily($type, CfrContextInterface $context = NULL);

}
