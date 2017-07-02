<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator\Partial;

use Drupal\cfrapi\CfrSchema\Callback\CallbackSchemaInterface;
use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchema\Drilldown\DrilldownSchemaInterface;
use Drupal\cfrapi\CfrSchema\Group\GroupSchemaInterface;
use Drupal\cfrapi\CfrSchema\Iface\IfaceSchemaInterface;
use Drupal\cfrapi\CfrSchema\Optional\OptionalSchemaInterface;
use Drupal\cfrapi\CfrSchema\Options\OptionsSchemaInterface;
use Drupal\cfrapi\CfrSchema\Sequence\SequenceSchemaInterface;
use Drupal\cfrapi\CfrSchema\ValueToValue\ValueToValueSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrapi\Configurator\Configurator_DrilldownSchema;
use Drupal\cfrapi\Configurator\Configurator_ValueToValueSchema;
use Drupal\cfrapi\Configurator\Configurator_ValueToValueSchemaOptional;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Group\Configurator_Group;
use Drupal\cfrapi\Configurator\Group\Configurator_GroupSchema;
use Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\Configurator\Sequence\Configurator_Sequence;
use Drupal\cfrapi\Exception\UnsupportedSchemaException;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface;
use Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface;

class CfrSchemaToConfiguratorPartial_Hardcoded implements CfrSchemaToConfiguratorPartialInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  private $typeToConfigurator;

  /**
   * @var \Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface
   */
  private $paramToConfigurator;

  /**
   * @var \Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * @param \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $typeToConfigurator
   * @param \Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface $paramToConfigurator
   * @param \Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface $paramToLabel
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
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetConfigurator(
    CfrSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    if ($cfrSchema instanceof OptionalSchemaInterface) {
      return $cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator(
        $cfrSchema->getCfrSchema());
    }

    if ($cfrSchema instanceof ConfiguratorInterface) {
      return $cfrSchema;
    }

    if ($cfrSchema instanceof ValueToValueSchemaInterface) {
      $decoratedSchema = $cfrSchema->getDecorated();
      $decoratedConfigurator = $cfrSchemaToConfigurator->cfrSchemaGetConfigurator($decoratedSchema);
      return new Configurator_ValueToValueSchema($decoratedConfigurator, $cfrSchema);
    }

    if ($cfrSchema instanceof DrilldownSchemaInterface) {
      return new Configurator_DrilldownSchema(
        $cfrSchema,
        $cfrSchemaToConfigurator);
    }

    if ($cfrSchema instanceof OptionsSchemaInterface) {
      return new Configurator_LegendSelect($cfrSchema);
    }

    if ($cfrSchema instanceof GroupSchemaInterface) {
      return $this->groupSchemaGetConfigurator(
        $cfrSchema,
        $cfrSchemaToConfigurator);
    }

    if ($cfrSchema instanceof SequenceSchemaInterface) {
      $itemSchema = $cfrSchema->getItemSchema();
      $itemConfigurator = $cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator($itemSchema);
      // @todo What if there is no such configurator?
      return new Configurator_Sequence($itemConfigurator);
    }

    if ($cfrSchema instanceof IfaceSchemaInterface) {
      // @todo What about optionality?
      $interface = $cfrSchema->getInterface();
      $context = $cfrSchema->getContext();
      $configurator = $this->typeToConfigurator->typeGetConfigurator(
        $interface,
        $context);
      if (!$configurator) {
        throw new UnsupportedSchemaException("There is no configurator for interface $interface.");
      }
      return $configurator;
    }

    if ($cfrSchema instanceof CallbackSchemaInterface) {
      return $this->callbackSchemaGetConfigurator(
        $cfrSchema,
        $cfrSchemaToConfigurator);
    }

    // Not supported.
    return FALSE;
  }

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|false
   */
  public function cfrSchemaGetOptionalConfigurator(
    CfrSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    /*
    if ($cfrSchema instanceof OptionalSchemaInterface) {
      $requiredCfrSchema = $cfrSchema->getCfrSchema();
      $requiredConfigurator = $this->cfrSchemaGetConfigurator($requiredCfrSchema);
      if ($requiredConfigurator instanceof Optionable
      return new Configurator_LegendSelect(
        $cfrSchema);
    }
    /**/

    if ($cfrSchema instanceof ConfiguratorInterface) {
      if ($cfrSchema instanceof OptionalConfiguratorInterface) {
        return $cfrSchema;
      }
      else {
        return FALSE;
      }
    }

    if ($cfrSchema instanceof ValueToValueSchemaInterface) {
      return new Configurator_ValueToValueSchemaOptional(
        $cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator(
          $cfrSchema->getDecorated()),
        $cfrSchema,
        NULL);
    }

    if ($cfrSchema instanceof DrilldownSchemaInterface) {
      return new Configurator_DrilldownSchema(
        $cfrSchema,
        $cfrSchemaToConfigurator,
        FALSE);
    }

    if ($cfrSchema instanceof OptionsSchemaInterface) {
      return Configurator_LegendSelect::createOptional($cfrSchema);
    }

    if ($cfrSchema instanceof GroupSchemaInterface) {
      // @todo Find a solution to make groups optionable?
      return FALSE;
    }

    // Sequence is already optional.
    if ($cfrSchema instanceof SequenceSchemaInterface) {
      $itemConfigurator = $cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator(
        $cfrSchema->getItemSchema());
      // @todo What if there is no such configurator?
      return new Configurator_Sequence($itemConfigurator);
    }

    if ($cfrSchema instanceof IfaceSchemaInterface) {
      return $this->typeToConfigurator->typeGetOptionalConfigurator(
        $cfrSchema->getInterface(),
        $cfrSchema->getContext());
    }

    // Not supported.
    return FALSE;
  }

  /**
   * @param \Drupal\cfrapi\CfrSchema\Group\GroupSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return bool|\Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   */
  private function groupSchemaGetConfigurator(
    GroupSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    $configurators = [];
    foreach ($cfrSchema->getItemSchemas() as $k => $itemSchema) {
      $configurators[$k] = $cfrSchemaToConfigurator->cfrSchemaGetConfigurator($itemSchema);
    }

    return Configurator_Group::createFromConfigurators(
      $configurators,
      $cfrSchema->getLabels());
  }

  /**
   * @param \Drupal\cfrapi\CfrSchema\Callback\CallbackSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return bool|\Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   */
  private function callbackSchemaGetConfigurator(
    CallbackSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    $callback = $cfrSchema->getCallback();
    $params = $callback->getReflectionParameters();

    $explicitParamSchemas = $cfrSchema->getExplicitParamSchemas();
    $explicitParamLabels = $cfrSchema->getExplicitParamLabels();
    $context = $cfrSchema->getContext();

    $paramConfigurators = [];
    $paramLabels = [];
    foreach ($params as $i => $param) {

      if (isset($explicitParamSchemas[$i])) {
        $paramConfigurators[] = $cfrSchemaToConfigurator->cfrSchemaGetConfigurator(
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
