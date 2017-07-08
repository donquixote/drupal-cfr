<?php

namespace Donquixote\Cf\Legacy\SchemaToEmptyness;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Bool;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Enum;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Key;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

class SchemaToEmptyness_Hardcoded implements SchemaToEmptynessInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   */
  public function schemaGetEmptyness(CfSchemaInterface $schema) {

    if ($schema instanceof CfSchema_DrilldownInterface) {
      return new ConfEmptyness_Key('id');
    }

    if ($schema instanceof CfSchema_OptionsInterface) {
      return new ConfEmptyness_Enum();
    }

    if ($schema instanceof CfSchema_NeutralInterface) {
      return $this->schemaGetEmptyness($schema->getDecorated());
    }

    if ($schema instanceof CfSchema_ValueToValueInterface) {
      return $this->schemaGetEmptyness($schema->getDecorated());
    }

    if (0
      || $schema instanceof CfSchema_OptionlessInterface
      || $schema instanceof ValueProviderInterface
    ) {
      return new ConfEmptyness_Bool();
    }

    return NULL;
  }
}
