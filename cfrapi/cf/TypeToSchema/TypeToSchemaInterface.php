<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;

interface TypeToSchemaInterface {

  /**
   * @param string $type
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($type, CfContextInterface $context = NULL);

}
