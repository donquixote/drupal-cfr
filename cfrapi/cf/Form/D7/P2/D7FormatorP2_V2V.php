<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\Util\UtilBase;

final class D7FormatorP2_V2V extends UtilBase {

  /**
   * @C_f
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface|null
   */
  public static function create(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return StaUtil::formatorP2(
      $schema->getDecorated(),
      $schemaToAnything);
  }

  /**
   * @C_f
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\P2\Optionable\OptionableD7FormatorP2Interface|null
   */
  public static function createOptionable(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return StaUtil::formatorP2Optionable(
      $schema->getDecorated(),
      $schemaToAnything);
  }

}
