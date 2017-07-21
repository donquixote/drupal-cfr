<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class SchemaToAnything_SchemaReplacer implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   */
  public function __construct(SchemaReplacerInterface $replacer) {
    $this->replacer = $replacer;
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
    if (NULL === $replacement = $this->replacer->schemaGetReplacement($schema)) {
      return NULL;
    }

    return $helper->schema($replacement, $interface);
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function providesResultType($interface) {
    return TRUE;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function acceptsSchemaClass($interface) {
    return TRUE;
  }
}
