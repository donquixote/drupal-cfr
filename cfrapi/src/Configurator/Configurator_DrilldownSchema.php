<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;
use Drupal\cfrapi\PossiblyOptionless\PossiblyOptionlessInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;
use Drupal\cfrfamily\Configurator\Composite\Configurator_IdConfBase;

class Configurator_DrilldownSchema extends Configurator_IdConfBase {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $drilldownSchema;

  /**
   * @var \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface
   */
  private $schemaToConfigurator;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $drilldownSchema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   * @param bool $required
   */
  public function __construct(
    CfSchema_DrilldownInterface $drilldownSchema,
    SchemaToConfiguratorInterface $schemaToConfigurator,
    $required = TRUE
  ) {
    $this->drilldownSchema = $drilldownSchema;
    $this->schemaToConfigurator = $schemaToConfigurator;

    parent::__construct($required);
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
    if (NULL === $schema = $this->drilldownSchema->idGetSchema($id)) {
      return NULL;
    }

    try {
      return $this->schemaToConfigurator->schemaGetConfigurator(
        $schema);
    }
    catch (ConfiguratorCreationException $e) {
      dpm($schema, $e->getMessage());
      # dpm($e->getMessage(), get_class($schema));
      return NULL;
    }
  }
}
