<?php

namespace Donquixote\Cf\TypeToSchema;

use Drupal\cfrapi\Context\CfrContextInterface;

interface TypeToSchemaInterface {

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($type, CfrContextInterface $context = NULL);

}
