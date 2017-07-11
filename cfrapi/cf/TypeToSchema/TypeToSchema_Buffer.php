<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;

class TypeToSchema_Buffer implements TypeToSchemaInterface {

  /**
   * @var \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $schemas = [];

  /**
   * @param \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $decorated
   */
  public function __construct(TypeToSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $type
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($type, CfContextInterface $context = NULL) {
    return array_key_exists($type, $this->schemas)
      ? $this->schemas[$type]
      : $this->schemas[$type] = $this->decorated->typeGetCfrSchema($type, $context);
  }

}
