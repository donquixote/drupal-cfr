<?php

namespace Donquixote\Cf\Form\Common;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelperInterface;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;

/**
 * @Cf
 */
class FormatorCommon_V2V implements SchemaToAnythingPartialInterface {

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

    if (!$schema instanceof CfSchema_ValueToValueBaseInterface) {
      return NULL;
    }

    return $helper->schema($schema->getDecorated(), $interface);
  }

  /**
   * @param string $resultInterface
   *
   * @return bool
   */
  public function providesResultType($resultInterface) {
    return is_a(
      $resultInterface ,
      FormatorCommonInterface::class,
      TRUE);
  }

  /**
   * @param string $schemaClass
   *
   * @return bool
   */
  public function acceptsSchemaClass($schemaClass) {
    return is_a(
      $schemaClass,
      CfSchema_ValueToValueBaseInterface::class,
      TRUE);
  }
}
