<?php

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaToAnythingPartialInterface {

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
    SchemaToAnythingHelperInterface $helper);

  /**
   * @param string $resultInterface
   *
   * @return bool
   */
  public function providesResultType($resultInterface);

  /**
   * @param string $schemaClass
   *
   * @return bool
   */
  public function acceptsSchemaClass($schemaClass);

  /**
   * @return int
   */
  public function getSpecifity();

}
