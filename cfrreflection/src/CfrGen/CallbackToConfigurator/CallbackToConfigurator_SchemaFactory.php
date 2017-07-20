<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;

/**
 * Creates a configurator for a callback, where the callback return value is the
 * configurator, and the callback parameters represent the context.
 */
class CallbackToConfigurator_SchemaFactory extends CallbackToConfiguratorBase {

  /**
   * @var \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface
   */
  private $schemaToConfigurator;

  /**
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   */
  public function __construct(SchemaToConfiguratorInterface $schemaToConfigurator) {
    $this->schemaToConfigurator = $schemaToConfigurator;
  }

  /**
   * @param mixed $schemaCandidate
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\ConfiguratorCreationException
   */
  protected function candidateGetConfigurator($schemaCandidate) {

    if (!$schemaCandidate instanceof CfSchemaInterface) {
      if (is_object($schemaCandidate)) {
        $class = get_class($schemaCandidate);
        throw new ConfiguratorCreationException("The schema factory is expected to return a CfSchema object, but returned a $class object instead.");
      }
      else {
        $export = var_export($schemaCandidate, TRUE);
        throw new ConfiguratorCreationException("The schema factory returned non-object value $export.");
      }
    }

    return $this->schemaToConfigurator->schemaGetConfigurator($schemaCandidate);
  }
}
