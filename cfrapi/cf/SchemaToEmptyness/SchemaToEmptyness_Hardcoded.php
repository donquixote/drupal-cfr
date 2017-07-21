<?php

namespace Donquixote\Cf\SchemaToEmptyness;

use Donquixote\Cf\Emptyness\Emptyness_Bool;
use Donquixote\Cf\Emptyness\Emptyness_Enum;
use Donquixote\Cf\Emptyness\Emptyness_Key;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

class SchemaToEmptyness_Hardcoded implements SchemaToEmptynessInterface {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Emptyness\EmptynessInterface|null
   */
  public static function sta(CfSchemaInterface $schema) {
    return (new self)->schemaGetEmptyness($schema);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Emptyness\EmptynessInterface|null
   */
  public function schemaGetEmptyness(CfSchemaInterface $schema) {

    if ($schema instanceof CfSchema_DrilldownInterface) {
      return new Emptyness_Key('id');
    }

    if ($schema instanceof CfSchema_OptionsInterface) {
      return new Emptyness_Enum();
    }

    if ($schema instanceof CfSchema_NeutralInterface) {
      return $this->schemaGetEmptyness($schema->getDecorated());
    }

    if ($schema instanceof CfSchema_ValueToValueBaseInterface) {
      return $this->schemaGetEmptyness($schema->getDecorated());
    }

    if ($schema instanceof CfSchema_OptionlessInterface) {
      return new Emptyness_Bool();
    }

    return NULL;
  }
}
