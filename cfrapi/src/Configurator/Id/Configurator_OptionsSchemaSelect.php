<?php

namespace Drupal\cfrapi\Configurator\Id;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Drupal\cfrapi\Exception\ConfToValueException;

class Configurator_OptionsSchemaSelect extends Configurator_SelectBase {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $optionsSchema;

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
    return $this->optionsSchema->idGetValue($id);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {

    try {
      $id = parent::confGetValue($conf);
    }
    catch (ConfToValueException $e) {
      return $helper->incompatibleConfiguration($conf, $e->getMessage());
    }

    return $this->optionsSchema->idGetPhp($id, $helper);
  }
}
