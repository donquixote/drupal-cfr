<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;
use Drupal\cfrapi\PossiblyOptionless\PossiblyOptionlessInterface;
use Drupal\cfrfamily\Configurator\Composite\Configurator_IdConfBase;
use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;

class Configurator_DrilldownSchema extends Configurator_IdConfBase {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $drilldownSchema;

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   */
  private $cfrSchemaToConfigurator;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $drilldownSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   * @param bool $required
   */
  public function __construct(
    CfSchema_DrilldownInterface $drilldownSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator,
    $required = TRUE
  ) {
    $this->drilldownSchema = $drilldownSchema;
    $this->cfrSchemaToConfigurator = $cfrSchemaToConfigurator;

    parent::__construct(
      $required,
      ($drilldownSchema instanceof IdValueToValueInterface)
        ? $drilldownSchema
        : NULL);
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {

    $options = $this->drilldownSchema->getGroupedOptions();

    foreach ($options as /* $groupLabel => */ &$groupOptions) {
      foreach ($groupOptions as $id => &$label) {
        if (NULL === $schema = $this->drilldownSchema->idGetSchema($id)) {
          unset($groupOptions[$id]);
          continue;
        }
        if ($schema instanceof CfSchema_OptionlessInterface) {
          continue;
        }
        if ($schema instanceof PossiblyOptionlessInterface) {
          if ($schema->isOptionless()) {
            continue;
          }
        }
        $label .= '…';
      }

      asort($groupOptions);
    }

    ksort($options);

    if (!empty($options[''])) {
      $options = $options[''] + $options;
    }
    unset($options['']);

    return $options;
  }

  /**
   * @param string $id
   *
   * @return string
   */
  protected function idGetLabel($id) {
    return $this->drilldownSchema->idGetLabel($id);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  protected function idGetConfigurator($id) {

    // @todo Cache this!
    if (NULL === $cfrSchema = $this->drilldownSchema->idGetSchema($id)) {
      return NULL;
    }

    try {
      return $this->cfrSchemaToConfigurator->cfrSchemaGetConfigurator(
        $cfrSchema);
    }
    catch (ConfiguratorCreationException $e) {
      # dpm($e->getMessage(), get_class($cfrSchema));
      return NULL;
    }
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
    return $this->drilldownSchema->idValueGetValue($id, $value);
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
    return $this->drilldownSchema->idPhpGetPhp($id, $php);
  }
}
