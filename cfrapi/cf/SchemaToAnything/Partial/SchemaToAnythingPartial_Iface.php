<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_Iface;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\TypeToSchema\TypeToSchemaInterface;

class SchemaToAnythingPartial_Iface implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   */
  private $typeToSchema;

  /**
   * @param \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $typeToSchema
   */
  public function __construct(TypeToSchemaInterface $typeToSchema) {
    $this->typeToSchema = $typeToSchema;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function schema(
    CfSchemaInterface $schema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {
    if (!$schema instanceof CfSchema_Iface) {
      return NULL;
    }

    $schema = $this->typeToSchema->typeGetCfrSchema(
      $schema->getInterface(),
      $schema->getContext());

    if (NULL === $schema) {
      return NULL;
    }

    return $helper->schema($schema, $interface);
  }
}
