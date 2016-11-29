<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

class Configurator_CfrSchema extends Configurator_DecoratorBase {

  /**
   * @var \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   */
  private $cfrSchema;

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   */
  public function __construct(CfrSchemaInterface $cfrSchema) {
    $this->cfrSchema = $cfrSchema;
    parent::__construct($cfrSchema->getConfigurator());
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    return $this->cfrSchema->valueGetValue($value);
  }

}
