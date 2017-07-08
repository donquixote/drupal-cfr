<?php

namespace Donquixote\Cf\Schema\Optionless;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

class CfSchema_Optionless_OptionsSchemaId implements CfSchema_OptionlessInterface {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $optionsSchema;

  /**
   * @var int|string
   */
  private $id;

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $optionsSchema
   * @param string|int $id
   */
  public function __construct(CfSchema_OptionsInterface $optionsSchema, $id) {
    $this->optionsSchema = $optionsSchema;
    $this->id = $id;
  }

  /**
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function getValue() {
    return $this->optionsSchema->idGetValue($this->id);
  }

  /**
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(CfrCodegenHelperInterface $helper) {
    return $this->optionsSchema->idGetPhp($this->id, $helper);
  }
}
