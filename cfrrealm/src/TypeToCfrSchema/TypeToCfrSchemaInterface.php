<?php

namespace Drupal\cfrrealm\TypeToCfrSchema;

use Drupal\cfrapi\Context\CfrContextInterface;

interface TypeToCfrSchemaInterface {

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($type, CfrContextInterface $context = NULL);

}
