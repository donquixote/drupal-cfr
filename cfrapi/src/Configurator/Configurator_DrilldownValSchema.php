<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;

class Configurator_DrilldownValSchema extends Configurator_DrilldownSchema {

  /**
   * @var \Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface
   */
  private $drilldownValSchema;

  /**
   * @param \Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface $drilldownValSchema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   * @param bool $required
   */
  public function __construct(
    CfSchema_DrilldownValInterface $drilldownValSchema,
    SchemaToConfiguratorInterface $schemaToConfigurator,
    $required = TRUE
  ) {
    $this->drilldownValSchema = $drilldownValSchema;

    parent::__construct(
      $drilldownValSchema->getDecorated(),
      $schemaToConfigurator,
      $required);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function idConfGetValue($id, $optionsConf) {
    $value = parent::idConfGetValue($id, $optionsConf);
    return $this->drilldownValSchema->idValueGetValue($id, $value);
  }

  /**
   * @param string $id
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function idConfGetPhp($id, $conf, CfrCodegenHelperInterface $helper) {
    $php = parent::idConfGetPhp($id, $conf, $helper);
    return $this->drilldownValSchema->idPhpGetPhp($id, $php);
  }
}
