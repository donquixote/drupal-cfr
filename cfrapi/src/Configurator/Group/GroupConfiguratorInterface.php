<?php
namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;

/**
 * Configurator where the value is an array, with predefined type per key.
 *
 * This is useful e.g. for a function signature.
 */
interface GroupConfiguratorInterface extends ConfiguratorInterface {

  /**
   * Same formal signature, but returns an array as a value.
   *
   * @param mixed $conf
   *
   * @return mixed[]
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf);

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string[]
   *   PHP statements to generate the values.
   */
  public function confGetPhpStatements($conf, CfrCodegenHelperInterface $helper);

}
