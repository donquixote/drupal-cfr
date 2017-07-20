<?php

namespace Drupal\cfrapi\SchemaToConfigurator\Partial;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;

class SchemaToConfiguratorPartial_Callback implements SchemaToConfiguratorPartialInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   */
  public function __construct(CallbackReflectionInterface $callbackReflection) {
    $this->callbackReflection = $callbackReflection;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   */
  public function schemaGetConfigurator(
    CfSchemaInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    return $this->callbackReflection->invokeArgs([$schema, $schemaToConfigurator]);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|false
   */
  public function schemaGetOptionalConfigurator(
    CfSchemaInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    return FALSE;
  }
}
