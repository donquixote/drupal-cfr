<?php

namespace Drupal\cfrrealm\TypeToContainer;

use Drupal\cfrapi\Context\CfrContextInterface;

interface TypeToContainerInterface {

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface
   */
  public function typeGetContainer($type, CfrContextInterface $context = NULL);
}
