<?php

namespace Donquixote\Cf\Form\D7\Util;

use Donquixote\Cf\Form\D7\FormatorD7Interface;
use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\Util\UtilBase;

final class D7FormSTAUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public static function formator(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return StaUtil::getObject($schema, $schemaToAnything, FormatorD7Interface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public static function formatorOptional(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    $optionable = self::formatorOptionable(
      $schema,
      $schemaToAnything
    );

    if (NULL === $optionable) {
      kdpm('Sorry.', __METHOD__);
      return NULL;
    }

    return $optionable->getOptionalFormator();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface|null
   */
  public static function formatorOptionable(
    CfSchemaInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return StaUtil::getObject($schema, $schemaToAnything, OptionableFormatorD7Interface::class);
  }
}
