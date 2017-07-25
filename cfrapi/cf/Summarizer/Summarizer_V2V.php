<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\Util\UtilBase;

final class Summarizer_V2V extends UtilBase {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface|null
   */
  public static function create(
    CfSchema_ValueToValueBaseInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    return StaUtil::summarizer(
      $schema->getDecorated(),
      $schemaToAnything);
  }

}
