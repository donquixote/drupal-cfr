<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator\Partial;

use Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
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
use Drupal\cfrrealm\CfrSchema\CfSchema_Neutral_IfaceTransformed;
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
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetConfigurator(
    CfSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    if ($cfrSchema instanceof CfSchema_OptionalInterface) {
      return $cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator(
        $cfrSchema->getDecorated());
    }

    if ($cfrSchema instanceof ValueProviderInterface) {
      return new Configurator_FromValueProvider($cfrSchema);
    }

    if ($cfrSchema instanceof ConfiguratorInterface) {
      return $cfrSchema;
    }

    if ($cfrSchema instanceof CfSchema_ValueToValueInterface) {
      $decoratedSchema = $cfrSchema->getDecorated();
      $decoratedConfigurator = $cfrSchemaToConfigurator->cfrSchemaGetConfigurator($decoratedSchema);
      return new Configurator_ValueToValueSchema($decoratedConfigurator, $cfrSchema);
    }

    if ($cfrSchema instanceof CfSchema_DrilldownInterface) {
      return new Configurator_DrilldownSchema(
        $cfrSchema,
        $cfrSchemaToConfigurator);
    }

    if ($cfrSchema instanceof CfSchema_OptionsInterface) {
      return new Configurator_OptionsSchemaSelect($cfrSchema);
    }

    if ($cfrSchema instanceof CfSchema_GroupInterface) {
      return $this->groupSchemaGetConfigurator(
        $cfrSchema,
        $cfrSchemaToConfigurator);
    }

    if ($cfrSchema instanceof CfSchema_SequenceInterface) {
      $itemSchema = $cfrSchema->getItemSchema();
      $itemConfigurator = $cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator($itemSchema);
      // @todo What if there is no such configurator?
      return new Configurator_Sequence($itemConfigurator);
    }

    if ($cfrSchema instanceof CfSchema_IfaceInterface) {
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

    if ($cfrSchema instanceof CfSchema_Neutral_IfaceTransformed) {
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

    if ($cfrSchema instanceof CfSchema_CallbackInterface) {
      return $this->callbackSchemaGetConfigurator(
        $cfrSchema,
        $cfrSchemaToConfigurator);
    }

    // Not supported.
    return FALSE;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetOptionalConfigurator(
    CfSchemaInterface $cfrSchema,
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

    if ($cfrSchema instanceof CfSchema_ValueToValueInterface) {
      return new Configurator_ValueToValueSchemaOptional(
        $cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator(
          $cfrSchema->getDecorated()),
        $cfrSchema,
        NULL);
    }

    if ($cfrSchema instanceof CfSchema_DrilldownInterface) {
      return new Configurator_DrilldownSchema(
        $cfrSchema,
        $cfrSchemaToConfigurator,
        FALSE);
    }

    if ($cfrSchema instanceof CfSchema_OptionsInterface) {
      return Configurator_OptionsSchemaSelect::createOptional($cfrSchema);
    }

    if ($cfrSchema instanceof CfSchema_GroupInterface) {
      // @todo Find a solution to make groups optionable?
      return FALSE;
    }

    // Sequence is already optional.
    if ($cfrSchema instanceof CfSchema_SequenceInterface) {
      $itemConfigurator = $cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator(
        $cfrSchema->getItemSchema());
      // @todo What if there is no such configurator?
      return new Configurator_Sequence($itemConfigurator);
    }

    if ($cfrSchema instanceof CfSchema_IfaceInterface) {
      return $this->typeToConfigurator->typeGetOptionalConfigurator(
        $cfrSchema->getInterface(),
        $cfrSchema->getContext());
    }

    // Not supported.
    return FALSE;
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return bool|\Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  private function groupSchemaGetConfigurator(
    CfSchema_GroupInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    return new Configurator_GroupSchema(
      $cfrSchema,
      $cfrSchemaToConfigurator);
  }

  /**
   * @param \Donquixote\Cf\Schema\Callback\CfSchema_CallbackInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return bool|\Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  private function callbackSchemaGetConfigurator(
    CfSchema_CallbackInterface $cfrSchema,
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
