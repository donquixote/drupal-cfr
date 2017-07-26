<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\UtilBase;

final class FormatorD7_V2V extends UtilBase {

  /**
   * @C_f
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public static function create(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return D7FormSTAUtil::formator(
      $schema->getDecorated(),
      $schemaToAnything
    );
  }

  /**
   * @C_f
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface|null
   */
  public static function createOptionable(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return D7FormSTAUtil::formatorOptionable(
      $schema->getDecorated(),
      $schemaToAnything
    );
  }

}
