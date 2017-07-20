<?php

namespace Drupal\cfrapi\Configurator\Id;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

class Configurator_OptionsSchemaSelect extends Configurator_OptionsSchemaSelectBase {

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $optionsSchema
   * @param string|null $defaultId
   *
   * @return self
   */
  public static function createRequired(CfSchema_OptionsInterface $optionsSchema, $defaultId = NULL) {
    return new self($optionsSchema, TRUE, $defaultId);
  }

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $optionsSchema
   * @param string|null $defaultId
   *
   * @return self
   */
  public static function createOptional(CfSchema_OptionsInterface $optionsSchema, $defaultId = NULL) {
    return new self($optionsSchema, FALSE, $defaultId);
  }
}
