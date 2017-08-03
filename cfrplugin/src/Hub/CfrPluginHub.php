<?php

namespace Drupal\cfrplugin\Hub;

use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrplugin\DIC\CfrPluginRealmContainer;
use Drupal\cfrplugin\DIC\CfrPluginRealmContainerInterface;
use Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface;
use Drupal\cfrreflection\Util\StringUtil;

class CfrPluginHub implements CfrPluginHubInterface {

  /**
   * @var \Drupal\cfrplugin\DIC\CfrPluginRealmContainer|null
   */
  private static $container;

  /**
   * @var \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  private $interfaceToConfigurator;

  /**
   * @var \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $definitionsByTypeAndId;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @return \Drupal\cfrplugin\DIC\CfrPluginRealmContainer
   */
  public static function getContainer() {
    return NULL !== self::$container
      ? self::$container
      : self::$container = CfrPluginRealmContainer::createWithCache();
  }

  /**
   * @return \Drupal\cfrplugin\Hub\CfrPluginHubInterface
   */
  public static function create() {
    return self::createFromContainer(self::getContainer());
  }

  /**
   * @param \Drupal\cfrplugin\DIC\CfrPluginRealmContainerInterface $container
   *
   * @return \Drupal\cfrplugin\Hub\CfrPluginHub
   */
  public static function createFromContainer(CfrPluginRealmContainerInterface $container) {
    return new self(
      $container->typeToConfigurator,
      $container->definitionsByTypeAndId,
      $container->schemaToAnything);
  }

  /**
   * @param \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $interfaceToConfigurator
   * @param \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(
    TypeToConfiguratorInterface $interfaceToConfigurator,
    DefinitionsByTypeAndIdInterface $definitionsByTypeAndId,
    SchemaToAnythingInterface $schemaToAnything
  ) {
    $this->interfaceToConfigurator = $interfaceToConfigurator;
    $this->definitionsByTypeAndId = $definitionsByTypeAndId;
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * @return string[]
   */
  public function getInterfaceLabels() {

    $labels = [];
    foreach ($this->definitionsByTypeAndId->getDefinitionsByTypeAndId() as $interface => $definitions) {
      $labels[$interface] = StringUtil::interfaceGenerateLabel($interface);
    }

    return $labels;
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function interfaceGetConfigurator($interface, CfrContextInterface $context = NULL) {
    return $this->interfaceToConfigurator->typeGetConfigurator($interface, $context);
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function interfaceGetOptionalConfigurator($interface, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    return $this->interfaceToConfigurator->typeGetOptionalConfigurator($interface, $context, $defaultValue);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string|null $label
   *
   * @return array
   */
  public function schemaConfGetForm(CfSchemaInterface $schema, $conf, $label) {

    $formator = D7FormSTAUtil::formator(
      $schema,
      $this->schemaToAnything
    );

    if (NULL === $formator) {
      return [];
    }

    return $formator->confGetD7Form($conf, $label);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public function schemaGetFormator(CfSchemaInterface $schema) {

    return D7FormSTAUtil::formator(
      $schema,
      $this->schemaToAnything
    );
  }
}
