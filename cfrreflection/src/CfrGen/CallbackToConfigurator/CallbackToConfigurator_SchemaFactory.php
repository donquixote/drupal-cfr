<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;

/**
 * Creates a configurator for a callback, where the callback return value is the
 * configurator, and the callback parameters represent the context.
 */
class CallbackToConfigurator_SchemaFactory extends CallbackToConfiguratorBase {

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   */
  private $cfrSchemaToConfigurator;

  /**
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   */
  public function __construct(CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator) {
    $this->cfrSchemaToConfigurator = $cfrSchemaToConfigurator;
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
        throw new ConfiguratorCreationException("The schema factory is expected to return a CfrSchema object, but returned a $class object instead.");
      }
      else {
        $export = var_export($schemaCandidate, TRUE);
        throw new ConfiguratorCreationException("The schema factory returned non-object value $export.");
      }
    }

    return $this->cfrSchemaToConfigurator->cfrSchemaGetConfigurator($schemaCandidate);
  }
}
