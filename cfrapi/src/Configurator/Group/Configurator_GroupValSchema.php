<?php

namespace Drupal\cfrapi\Configurator\Group;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;

class Configurator_GroupValSchema extends Configurator_GroupSchema {

  /**
   * @var \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  private $groupValSchema;

  /**
   * @param \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface $groupValSchema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function __construct(
    CfSchema_GroupValInterface $groupValSchema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    parent::__construct(
      $groupValSchema->getDecorated(),
      $schemaToConfigurator);

    $this->groupValSchema = $groupValSchema;
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
    return $this->groupValSchema->valuesGetValue($values);
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
    return $this->groupValSchema->itemsPhpGetPhp($itemsPhp);
  }
}
