<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;

class Configurator_GroupSchema extends Configurator_GroupGrandBase {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $groupSchema;

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  private $configurators;

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function __construct(
    CfSchema_GroupInterface $groupSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    // Construct all configurators here, when throwing exceptions is still
    // allowed.
    $configurators = [];
    foreach ($groupSchema->getItemSchemas() as $k => $itemSchema) {
      $configurators[$k] = $cfrSchemaToConfigurator->cfrSchemaGetConfigurator($itemSchema);
    }

    $this->configurators = $configurators;
    $this->groupSchema = $groupSchema;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  protected function getConfigurators() {
    return $this->configurators;
  }

  /**
   * @return string[]
   */
  protected function getLabels() {
    return $this->groupSchema->getLabels();
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
    $values = parent::confGetValue($conf);
    return $this->groupSchema->valuesGetValue($values);
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
    $itemsPhp = parent::confGetPhpStatements($conf, $helper);
    return $this->groupSchema->itemsPhpGetPhp($itemsPhp);
  }
}
