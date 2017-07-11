<?php

namespace Donquixote\Cf\TypeToSchema;

use Donquixote\Cf\Context\CfrContextInterface;
use Donquixote\cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;

class TypeToSchema_AddTag implements TypeToSchemaInterface {

  /**
   * @var \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $decorated
   */
  public function __construct(TypeToSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function typeGetCfrSchema($interface, CfrContextInterface $context = NULL) {

    return new CfSchema_Neutral_IfaceTransformed(
      $this->decorated->typeGetCfrSchema($interface, $context),
      $interface,
      $context);
  }
}
