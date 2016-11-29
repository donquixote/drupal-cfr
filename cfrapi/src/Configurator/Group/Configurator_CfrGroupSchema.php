<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface;

class Configurator_CfrGroupSchema extends Configurator_GroupBase {

  /**
   * @var \Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface
   */
  private $groupSchema;

  /**
   * @param \Drupal\cfrapi\CfrGroupSchema\CfrGroupSchemaInterface $groupSchema
   */
  public function __construct(CfrGroupSchemaInterface $groupSchema) {
    $this->groupSchema = $groupSchema;
    $labels = $groupSchema->getLabels();
    foreach ($groupSchema->getConfigurators() as $k => $configurator) {
      $label = isset($labels[$k]) ? $labels[$k] : $k;
      $this->keySetConfigurator($k, $configurator, $label);
    }
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    if (!is_array($value)) {
      return $value;
    }
    return $this->groupSchema->valuesGetValue($value);
  }

}
