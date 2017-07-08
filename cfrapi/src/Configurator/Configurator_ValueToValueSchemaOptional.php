<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;

class Configurator_ValueToValueSchemaOptional extends Configurator_ValueToValueSchema implements OptionalConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $emptyness;

  /**
   * @var mixed|null
   */
  private $defaultValue;

  /**
   * @param \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface $decorated
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   * @param mixed $defaultValue
   */
  public function __construct(
    OptionalConfiguratorInterface $decorated,
    CfSchema_ValueToValueInterface $valueToValueSchema,
    $defaultValue = NULL
  ) {
    parent::__construct($decorated, $valueToValueSchema);
    $this->emptyness = $decorated->getEmptyness();
    $this->defaultValue = $defaultValue;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   *   An emptyness object, or
   *   NULL, if the configurator is in fact required and thus no valid conf
   *   counts as empty.
   */
  public function getEmptyness() {
    return $this->emptyness;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    if ($this->emptyness->confIsEmpty($conf)) {
      // @todo Throw exception?
      return $this->defaultValue;
    }
    return parent::confGetValue($conf);
  }

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    if ($this->emptyness->confIsEmpty($conf)) {
      // @todo Throw exception?
      return var_export($this->defaultValue, TRUE);
    }
    return parent::confGetValue($conf);
  }
}
