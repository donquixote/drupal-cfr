<?php

namespace Drupal\cfrapi\Configurator\Id;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

abstract class Configurator_OptionsSchemaSelectBase extends Configurator_SelectBase {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $optionsSchema;

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $optionsSchema
   * @param bool $required
   * @param string|null $defaultId
   */
  public function __construct(CfSchema_OptionsInterface $optionsSchema, $required = TRUE, $defaultId = NULL) {
    $this->optionsSchema = $optionsSchema;
    parent::__construct($required, $defaultId);
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {
    $options = $this->optionsSchema->getGroupedOptions();
    if (!empty($options[''])) {
      $options =  + $options;
    }
    unset($options['']);
    return $options;
  }

  /**
   * @param string $id
   *
   * @return string
   */
  protected function idGetLabel($id) {
    return $this->optionsSchema->idGetLabel($id);
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  protected function idIsKnown($id) {
    return $this->optionsSchema->idIsKnown($id);
  }
}
