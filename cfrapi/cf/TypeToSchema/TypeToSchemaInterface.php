<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfrContextInterface;

interface TypeToSchemaInterface {

  /**
   * @param string $type
   * @param \Donquixote\Cf\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($type, CfrContextInterface $context = NULL);

}
