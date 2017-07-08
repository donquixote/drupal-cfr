<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;

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
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {

    $paramLabel = $this->valueToValueSchema->getLabel();

    if (NULL === $label) {
      $label = $paramLabel;
    }
    elseif (NULL !== $this->valueToValueSchema->getLabel()) {
      $label .= ' | ' . $paramLabel;
    }

    return parent::confGetForm($conf, $label);
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
