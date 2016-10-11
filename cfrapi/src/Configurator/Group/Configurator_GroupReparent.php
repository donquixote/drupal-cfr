<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ElementProcessor\ElementProcessor_ReparentChildren;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\Util\ConfUtil;

class Configurator_GroupReparent extends Configurator_Group {

  /**
   * @var string[][]
   *   Format: $[$key][] = $parentKey
   */
  private $keysReparent = [];

  /**
   * @param string $key
   * @param string[] $parents
   *   Parents, indicating the new place for this key.
   *
   * @return $this
   */
  public function keySetParents($key, array $parents) {
    $this->keysReparent[$key] = $parents;
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
    $conf = $this->extractConf($conf);
    $form = parent::confGetForm($conf, $label);
    $form['#process'][] = new ElementProcessor_ReparentChildren($this->keysReparent);
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
    $conf = $this->extractConf($conf);
    return parent::confGetSummary($conf, $summaryBuilder);
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
    $conf = $this->extractConf($conf);
    $result = parent::confGetValue($conf);
    if (!is_array($result)) {
      return $result;
    }
    foreach (array_reverse($this->keysReparent) as $key => $parents) {
      if (isset($result[$key])) {
        $value = $result[$key];
        unset($result[$key]);
        if (is_array($value)) {
          ConfUtil::confMergeNestedValue($result, $parents, $value);
        }
        else {
          ConfUtil::confSetNestedValue($result, $parents, $value);
        }
      }
    }
    return $result;
  }

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string[]
   *   PHP statements to generate the values.
   */
  public function confGetPhpStatements($conf, CfrCodegenHelperInterface $helper) {
    $conf = $this->extractConf($conf);

    $php_statements = parent::confGetPhpStatements($conf, $helper);
    foreach (array_reverse($this->keysReparent) as $key => $parents) {
      if (isset($php_statements[$key])) {
        $php_statement = $php_statements[$key];
        unset($php_statements[$key]);
        if (is_array($php_statement)) {
          ConfUtil::confMergeNestedValue($result, $parents, $php_statement);
        }
        else {
          ConfUtil::confSetNestedValue($result, $parents, $php_statement);
        }
      }
    }

    return $php_statements;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed[]
   */
  private function extractConf($conf) {
    if (!is_array($conf)) {
      return [];
    }
    $confExtracted = [];
    foreach ($this->keysReparent as $key => $parents) {
      $confExtracted[$key] = ConfUtil::confExtractNestedValue($conf, $parents);
      ConfUtil::confUnsetNestedValue($conf, $parents);
    }
    return $confExtracted + $conf;
  }

}
