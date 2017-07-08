<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_Optionless_Null;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

class CfSchema_Drilldown_FromOptionsSchema implements CfSchema_DrilldownInterface {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $optionsSchema;

  /**
   * @var \Donquixote\Cf\Schema\Optionless\CfSchema_Optionless_FixedValue
   */
  private $nullSchema;

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $optionsSchema
   */
  public function __construct(CfSchema_OptionsInterface $optionsSchema) {
    $this->optionsSchema = $optionsSchema;
    $this->nullSchema = new CfSchema_Optionless_Null();
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions() {
    return $this->optionsSchema->getGroupedOptions();
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    return $this->optionsSchema->idGetLabel($id);
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return $this->optionsSchema->idIsKnown($id);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id) {
    return $this->idIsKnown($id)
      ? $this->nullSchema
      : NULL;
  }

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function idValueGetValue($id, $value) {
    return $this->optionsSchema->idGetValue($id);
  }

  /**
   * @param string|int $id
   * @param string $php
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, $php, CfrCodegenHelperInterface $helper) {
    return $this->optionsSchema->idGetPhp($id, $helper);
  }
}
