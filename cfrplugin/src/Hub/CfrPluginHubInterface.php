<?php
namespace Drupal\cfrplugin\Hub;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface;

interface CfrPluginHubInterface extends InterfaceToConfiguratorInterface {

  /**
   * @return string[]
   *   Format: $[$interface] = $label
   */
  public function getInterfaceLabels();

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string|null $label
   *
   * @return array
   */
  public function schemaConfGetForm(CfSchemaInterface $schema, $conf, $label);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public function schemaGetFormator(CfSchemaInterface $schema);
}
