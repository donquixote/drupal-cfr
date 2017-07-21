<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;

/**
 * This is a version of TypeToSchema* where $type is assumed to be an interface
 * name.
 */
class TypeToSchema_Iface implements TypeToSchemaInterface {

  /**
   * @param string $type
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetSchema($type, CfContextInterface $context = NULL) {
    return new CfSchema_IfaceWithContext($type, $context);
  }
}
