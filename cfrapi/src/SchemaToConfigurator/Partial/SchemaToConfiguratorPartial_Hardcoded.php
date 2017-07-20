<?php

namespace Drupal\cfrapi\SchemaToConfigurator\Partial;

use Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;
use Drupal\cfrapi\Configurator\Configurator_DrilldownSchema;
use Drupal\cfrapi\Configurator\Configurator_ValueToValueSchema;
use Drupal\cfrapi\Configurator\Configurator_ValueToValueSchemaOptional;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Group\Configurator_GroupSchema;
use Drupal\cfrapi\Configurator\Id\Configurator_OptionsSchemaSelect;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\Configurator\Sequence\Configurator_Sequence;
use Drupal\cfrapi\Configurator\Unconfigurable\Configurator_FromValueProvider;
use Drupal\cfrapi\Exception\UnsupportedSchemaException;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface;
use Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Donquixote\Cf\ParamToLabel\ParamToLabelInterface;

class SchemaToConfiguratorPartial_Hardcoded implements SchemaToConfiguratorPartialInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  private $typeToConfigurator;

  /**
   * @var \Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface
   */
  private $paramToConfigurator;

  /**
   * @var \Donquixote\Cf\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * @param \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $typeToConfigurator
   * @param \Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface $paramToConfigurator
   * @param \Donquixote\Cf\ParamToLabel\ParamToLabelInterface $paramToLabel
   */
  public function __construct(
    TypeToConfiguratorInterface $typeToConfigurator,
    ParamToConfiguratorInterface $paramToConfigurator,
    ParamToLabelInterface $paramToLabel
  ) {
    $this->typeToConfigurator = $typeToConfigurator;
    $this->paramToConfigurator = $paramToConfigurator;
    $this->paramToLabel = $paramToLabel;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetConfigurator(
    CfSchemaInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    if ($schema instanceof CfSchema_OptionalInterface) {
      return $schemaToConfigurator->schemaGetOptionalConfigurator(
        $schema->getDecorated());
    }

    if ($schema instanceof ValueProviderInterface) {
      return new Configurator_FromValueProvider($schema);
    }

    if ($schema instanceof ConfiguratorInterface) {
      return $schema;
    }

    if ($schema instanceof CfSchema_ValueToValueInterface) {
      $decoratedSchema = $schema->getDecorated();
      $decoratedConfigurator = $schemaToConfigurator->schemaGetConfigurator($decoratedSchema);
      return new Configurator_ValueToValueSchema($decoratedConfigurator, $schema);
    }

    if ($schema instanceof CfSchema_DrilldownInterface) {
      return new Configurator_DrilldownSchema(
        $schema,
        $schemaToConfigurator);
    }

    if ($schema instanceof CfSchema_OptionsInterface) {
      return new Configurator_OptionsSchemaSelect($schema);
    }

    if ($schema instanceof CfSchema_GroupInterface) {
      return $this->groupSchemaGetConfigurator(
        $schema,
        $schemaToConfigurator);
    }

    if ($schema instanceof CfSchema_SequenceInterface) {
      $itemSchema = $schema->getItemSchema();
      $itemConfigurator = $schemaToConfigurator->schemaGetOptionalConfigurator($itemSchema);
      // @todo What if there is no such configurator?
      return new Configurator_Sequence($itemConfigurator);
    }

    if ($schema instanceof CfSchema_IfaceWithContextInterface) {
      // @todo What about optionality?
      $interface = $schema->getInterface();
      $context = $schema->getContext();
      $configurator = $this->typeToConfigurator->typeGetConfigurator(
        $interface,
        $context);
      if (!$configurator) {
        throw new UnsupportedSchemaException("There is no configurator for interface $interface.");
      }
      return $configurator;
    }

    if ($schema instanceof CfSchema_Neutral_IfaceTransformed) {
      $interface = $schema->getInterface();
      $context = $schema->getContext();
      $configurator = $this->typeToConfigurator->typeGetConfigurator(
        $interface,
        $context);
      if (!$configurator) {
        throw new UnsupportedSchemaException("There is no configurator for interface $interface.");
      }
      return $configurator;
    }

    if ($schema instanceof CfSchema_CallbackInterface) {
      return $this->callbackSchemaGetConfigurator(
        $schema,
        $schemaToConfigurator);
    }

    // Not supported.
    return FALSE;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetOptionalConfigurator(
    CfSchemaInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {

    if ($schema instanceof ConfiguratorInterface) {
      if ($schema instanceof OptionalConfiguratorInterface) {
        return $schema;
      }
      else {
        return FALSE;
      }
    }

    if ($schema instanceof CfSchema_ValueToValueInterface) {
      return new Configurator_ValueToValueSchemaOptional(
        $schemaToConfigurator->schemaGetOptionalConfigurator(
          $schema->getDecorated()),
        $schema,
        NULL);
    }

    if ($schema instanceof CfSchema_DrilldownInterface) {
      return new Configurator_DrilldownSchema(
        $schema,
        $schemaToConfigurator,
        FALSE);
    }

    if ($schema instanceof CfSchema_OptionsInterface) {
      return Configurator_OptionsSchemaSelect::createOptional($schema);
    }

    if ($schema instanceof CfSchema_GroupInterface) {
      // @todo Find a solution to make groups optionable?
      return FALSE;
    }

    // Sequence is already optional.
    if ($schema instanceof CfSchema_SequenceInterface) {
      $itemConfigurator = $schemaToConfigurator->schemaGetOptionalConfigurator(
        $schema->getItemSchema());
      // @todo What if there is no such configurator?
      return new Configurator_Sequence($itemConfigurator);
    }

    if ($schema instanceof CfSchema_IfaceWithContextInterface) {
      return $this->typeToConfigurator->typeGetOptionalConfigurator(
        $schema->getInterface(),
        $schema->getContext());
    }

    // Not supported.
    return FALSE;
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return bool|\Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  private function groupSchemaGetConfigurator(
    CfSchema_GroupInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    return new Configurator_GroupSchema(
      $schema,
      $schemaToConfigurator);
  }

  /**
   * @param \Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return bool|\Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  private function callbackSchemaGetConfigurator(
    CfSchema_CallbackInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    $callback = $schema->getCallback();
    $params = $callback->getReflectionParameters();

    $explicitParamSchemas = $schema->getExplicitParamSchemas();
    $explicitParamLabels = $schema->getExplicitParamLabels();
    $context = $schema->getContext();

    $paramConfigurators = [];
    $paramLabels = [];
    foreach ($params as $i => $param) {

      if (isset($explicitParamSchemas[$i])) {
        $paramConfigurators[] = $schemaToConfigurator->schemaGetConfigurator(
          $explicitParamSchemas[$i]);
      }
      elseif ($paramConfigurator = $this->paramToConfigurator->paramGetConfigurator(
        $param,
        $context)
      ) {
        $paramConfigurators[] = $paramConfigurator;
      }
      else {
        return FALSE;
      }

      if (isset($explicitParamLabels[$i])) {
        $paramLabels[] = $explicitParamLabels[$i];
      }
      else {
        $paramLabels[] = $this->paramToLabel->paramGetLabel($param);
      }
    }

    return new Configurator_CallbackConfigurable(
      $callback,
      $paramConfigurators,
      $paramLabels);
  }
}
