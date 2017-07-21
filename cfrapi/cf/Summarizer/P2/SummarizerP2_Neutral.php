<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\Util\UtilBase;

final class SummarizerP2_Neutral extends UtilBase {


  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  public static function create(CfSchema_NeutralInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return StaUtil::summarizerP2($schema->getDecorated(), $schemaToAnything);
  }
}
