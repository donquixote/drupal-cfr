<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class Configurator_ValueToValueSchema extends Configurator_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface
   */
  private $valueToValueSchema;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $decorated
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   */
  public function __construct(
    ConfiguratorInterface $decorated,
    CfSchema_ValueToValueInterface $valueToValueSchema
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
    return $this->valueToValueSchema->phpGetPhp($php);
  }
}
