<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;

class PartialEvaluator_Options implements PartialEvaluatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface $helper
   *
   * @return bool|null
   *   TRUE, if $conf is both valid and empty.
   *   FALSE, if $conf is either invalid or non-empty.
   *   NULL, to let another partial decide.
   */
  public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf, EmptynessHelperInterface $helper) {

    if (!$schema instanceof CfSchema_OptionsInterface) {
      return $helper->unknownSchema();
    }

    return NULL === $this->confGetId($conf);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $optionsSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetValue(CfSchemaInterface $optionsSchema, $conf, EvaluatorHelperInterface $helper) {

    if (!$optionsSchema instanceof CfSchema_OptionsInterface) {
      return $helper->unknownSchema();
    }

    if (NULL === $id = $this->confGetId($conf)) {
      return $helper->invalidConfiguration('Required id empty for options schema.');
    }

    if (!$optionsSchema->idIsKnown($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' for options schema.");
    }

    return $optionsSchema->idGetValue($id);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $optionsSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $optionsSchema, $conf, PhpHelperInterface $helper) {

    if (!$optionsSchema instanceof CfSchema_OptionsInterface) {
      return $helper->unknownSchema();
    }

    if (NULL === $id = $this->confGetId($conf)) {
      return $helper->invalidConfiguration('Required id empty for options schema.');
    }

    if (!$optionsSchema->idIsKnown($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' for options schema.");
    }

    return $optionsSchema->idGetPhp($id, $helper->getCodegenHelper());
  }

  /**
   * @param mixed $conf
   *
   * @return string|null
   */
  private function confGetId($conf) {

    if (is_numeric($conf)) {
      return (string)$conf;
    }

    if (NULL === $conf || '' === $conf || !is_string($conf)) {
      return NULL;
    }

    return $conf;
  }
}
