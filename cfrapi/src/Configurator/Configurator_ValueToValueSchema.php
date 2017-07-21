<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class Configurator_ValueToValueSchema extends Configurator_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface
   */
  private $v2v;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $decorated
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   */
  public function __construct(
    ConfiguratorInterface $decorated,
    CfSchema_ValueToValueInterface $valueToValueSchema
  ) {
    parent::__construct($decorated);
    $this->v2v = $valueToValueSchema->getV2V();
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
    return $this->v2v->valueGetValue($value);
  }

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    $php = parent::confGetPhp($conf, $helper);
    return $this->v2v->phpGetPhp($php);
  }
}
