<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\TypeToSchema\TypeToSchemaInterface;

class SchemaToAnythingPartial_Iface extends SchemaToAnythingPartialBase {

  /**
   * @var \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   */
  private $typeToSchema;

  /**
   * @param \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $typeToSchema
   */
  public function __construct(TypeToSchemaInterface $typeToSchema) {
    $this->typeToSchema = $typeToSchema;
    parent::__construct(CfSchema_IfaceWithContext::class, NULL);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $ifaceSchema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   */
  public function schemaDoGetObject(
    CfSchemaInterface $ifaceSchema,
    $interface,
    SchemaToAnythingHelperInterface $helper
  ) {

    /** @var \Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext $ifaceSchema */

    $schema = $this->typeToSchema->typeGetSchema(
      $ifaceSchema->getInterface(),
      $ifaceSchema->getContext());

    if (NULL === $schema) {
      return NULL;
    }

    return $helper->schema($schema, $interface);
  }
}
