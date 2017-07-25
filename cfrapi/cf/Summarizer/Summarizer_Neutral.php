<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\Util\UtilBase;

final class Summarizer_Neutral extends UtilBase {


  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  public static function create(CfSchema_NeutralInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return StaUtil::summarizer($schema->getDecorated(), $schemaToAnything);
  }
}
