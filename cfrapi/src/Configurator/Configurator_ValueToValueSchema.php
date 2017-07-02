<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\CfrSchema\ValueToValue\ValueToValueSchemaInterface;

class Configurator_ValueToValueSchema extends Configurator_DecoratorBase {

  /**
   * @var \Drupal\cfrapi\CfrSchema\ValueToValue\ValueToValueSchemaInterface
   */
  private $valueToValueSchema;

  /**
   * @param \Drupal\cfrapi\CfrSchema\ValueToValue\ValueToValueSchemaInterface $valueToValueSchema
   */
  public function __construct(
    ConfiguratorInterface $decorated,
    ValueToValueSchemaInterface $valueToValueSchema
  ) {
    parent::__construct($decorated);
    $this->valueToValueSchema = $valueToValueSchema;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    return $this->valueToValueSchema->valueGetValue($value);
  }

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    $php = parent::confGetPhp($conf, $helper);
    return $this->valueToValueSchema->phpGetPhp($php, $helper);
  }
}
