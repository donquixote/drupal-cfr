<?php

namespace Drupal\cfrapi\Configurator\Id;

use Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface;

class Configurator_OptionsValSchemaSelect extends Configurator_OptionsSchemaSelectBase {

  /**
   * @var \Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface
   */
  private $optionsValSchema;

  /**
   * @param \Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface $optionsValSchema
   * @param string|null $defaultId
   *
   * @return self
   */
  public static function createRequired(CfSchema_OptionsValInterface $optionsValSchema, $defaultId = NULL) {
    return new self($optionsValSchema, TRUE, $defaultId);
  }

  /**
   * @param \Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface $optionsValSchema
   * @param string|null $defaultId
   *
   * @return self
   */
  public static function createOptional(CfSchema_OptionsValInterface $optionsValSchema, $defaultId = NULL) {
    return new self($optionsValSchema, FALSE, $defaultId);
  }

  /**
   * @param \Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface $optionsValSchema
   * @param bool $required
   * @param string|null $defaultId
   */
  public function __construct(CfSchema_OptionsValInterface $optionsValSchema, $required = TRUE, $defaultId = NULL) {
    parent::__construct($optionsValSchema->getDecorated(), $required, $defaultId);
    $this->optionsValSchema = $optionsValSchema;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    $id = parent::confGetValue($conf);
    return $this->optionsValSchema->idGetValue($id);
  }

  /**
   * @param string|int $id
   *
   * @return string
   */
  protected function idGetPhp($id) {
    return $this->optionsValSchema->idGetPhp($id);
  }
}
