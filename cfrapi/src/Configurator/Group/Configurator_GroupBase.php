<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

/**
 * Allows to inherit group configurator functionality, without implementing
 * GroupConfiguratorInterface.
 */
abstract class Configurator_GroupBase implements ConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  private $configurators = [];

  /**
   * @var string[]
   */
  private $labels = [];

  /**
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return static
   */
  public static function createFromConfigurators(array $paramConfigurators, array $labels) {
    $groupConfigurator = new static();
    foreach ($paramConfigurators as $k => $paramConfigurator) {
      $paramLabel = isset($labels[$k]) ? $labels[$k] : $k;
      $groupConfigurator->keySetConfigurator($k, $paramConfigurator, $paramLabel);
    }
    return $groupConfigurator;
  }

  /**
   * @param string $key
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   * @param string $label
   *
   * @return $this
   */
  public function keySetConfigurator($key, ConfiguratorInterface $configurator, $label) {
    if ('#' === $key[0]) {
      throw new \InvalidArgumentException("Key '$key' must not begin with '#'.");
    }
    $this->configurators[$key] = $configurator;
    $this->labels[$key] = $label;
    return $this;
  }

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
    foreach ($this->configurators as $key => $configurator) {
      $keyConf = isset($conf[$key]) ? $conf[$key] : NULL;
      $form[$key] = $configurator->confGetForm($keyConf, $this->labels[$key]);
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
    $group = $summaryBuilder->startGroup();
    foreach ($this->configurators as $key => $configurator) {
      $keyConf = array_key_exists($key, $conf) ? $conf[$key] : NULL;
      $group->addSetting($this->labels[$key], $configurator, $keyConf);
    }
    return $group->buildSummary();
  }

  /**
   * Builds the value based on the given configuration.
   *
   * @param mixed[]|mixed $conf
   *
   * @return mixed[]|\Drupal\cfrapi\BrokenValue\BrokenValueInterface
   */
  public function confGetValue($conf) {
    if (!is_array($conf)) {
      // If all values are optional, this might still work.
      $conf = [];
    }
    $values = [];
    foreach ($this->configurators as $key => $configurator) {
      if (array_key_exists($key, $conf)) {
        $value = $configurator->confGetValue($conf[$key]);
      }
      else {
        $value = $configurator->confGetValue(NULL);
      }
      $values[$key] = $value;
      if ($value instanceof BrokenValueInterface) {
        return new BrokenValue($this, get_defined_vars(), "Value for key '$key' is broken.");
      }
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

    if (array() === $this->configurators) {
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
    foreach ($this->configurators as $key => $configurator) {

      $key_conf = array_key_exists($key, $conf)
        ? $conf[$key]
        : NULL;

      $php_statements[$key] = $configurator->confGetPhp($key_conf, $helper);
    }

    return $php_statements;
  }

}
