<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

/**
 * Allows to inherit group configurator functionality, without implementing
 * GroupConfiguratorInterface.
 */
abstract class Configurator_GroupGrandBase implements ConfiguratorInterface {

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   *   A form element(s) array.
   */
  public function confGetForm($conf, $label) {
    if (!is_array($conf)) {
      $conf = [];
    }
    $form = [];
    if (NULL !== $label && '' !== $label) {
      $form['#title'] = $label;
    }

    $labels = $this->getLabels();

    foreach ($this->getConfigurators() as $key => $configurator) {
      $keyConf = isset($conf[$key]) ? $conf[$key] : NULL;
      $form[$key] = $configurator->confGetForm($keyConf, $labels[$key]);
    }
    return $form;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    if (!is_array($conf)) {
      $conf = [];
    }

    $labels = $this->getLabels();

    $group = $summaryBuilder->startGroup();
    foreach ($this->getConfigurators() as $key => $configurator) {
      $keyConf = array_key_exists($key, $conf) ? $conf[$key] : NULL;
      $group->addSetting($labels[$key], $configurator, $keyConf);
    }

    return $group->buildSummary();
  }

  /**
   * Builds the value based on the given configuration.
   *
   * @param mixed[]|mixed $conf
   *
   * @return mixed[]
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf) {

    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }

    $values = [];
    foreach ($this->getConfigurators() as $key => $configurator) {
      if (array_key_exists($key, $conf)) {
        $value = $configurator->confGetValue($conf[$key]);
      }
      else {
        $value = $configurator->confGetValue(NULL);
      }
      $values[$key] = $value;
    }

    return $values;
  }


  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {

    if (array() === $this->getConfigurators()) {
      return '[]';
    }

    $php = '';
    foreach ($this->confGetPhpStatements($conf, $helper) as $key => $php_statement) {
      $php .= "\n  " . var_export($key, TRUE) . ' => ' . $php_statement . ',';
    }

    return "[$php\n]";
  }

  /**
   * Allows this to be subclassed implementing GroupConfiguratorInterface.
   *
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string[]
   *   PHP statements to generate the values.
   *
   * @see GroupConfiguratorInterface::confGetPhpStatements()
   */
  public function confGetPhpStatements($conf, CfrCodegenHelperInterface $helper) {

    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = array();
    }

    $php_statements = array();
    foreach ($this->getConfigurators() as $key => $configurator) {

      $key_conf = array_key_exists($key, $conf)
        ? $conf[$key]
        : NULL;

      $php_statements[$key] = $configurator->confGetPhp($key_conf, $helper);
    }

    return $php_statements;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  abstract protected function getConfigurators();

  /**
   * @return string[]
   */
  abstract protected function getLabels();

}
