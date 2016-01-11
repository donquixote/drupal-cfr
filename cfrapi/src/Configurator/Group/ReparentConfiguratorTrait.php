<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\Util\ConfUtil;
use Drupal\cfrapi\ElementProcessor\ElementProcessor_ReparentChildren;

trait ReparentConfiguratorTrait {

  /**
   * @var string[][]
   *   Format: $[$key][] = $parentKey
   */
  private $keysReparent = array();

  /**
   * @param string $key
   * @param string[] $parents
   *   Parents, indicating the new place for this key.
   *
   * @return $this
   */
  function keySetParents($key, array $parents) {
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
  function confGetForm($conf, $label) {
    $conf = $this->extractConf($conf);
    $form = $this->parentConfGetForm($conf, $label);
    $form['#process'][] = new ElementProcessor_ReparentChildren($this->keysReparent);
    return $form;
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   *   A form element(s) array.
   */
  abstract protected function parentConfGetForm($conf, $label);

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    $conf = $this->extractConf($conf);
    return $this->parentConfGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  abstract protected function parentConfGetSummary($conf, SummaryBuilderInterface $summaryBuilder);

  /**
   * Builds the value based on the given configuration.
   *
   * @param mixed[]|mixed $conf
   *
   * @return mixed[]|\Drupal\cfrapi\BrokenValue\BrokenValueInterface
   */
  function confGetValue($conf) {
    $conf = $this->extractConf($conf);
    $result = $this->parentConfGetValue($conf);
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
   * Builds the value based on the given configuration.
   *
   * @param mixed[]|mixed $conf
   *
   * @return mixed[]|\Drupal\cfrapi\BrokenValue\BrokenValueInterface
   */
  abstract protected function parentConfGetValue($conf);

  /**
   * @param mixed $conf
   *
   * @return mixed[]
   */
  private function extractConf($conf) {
    if (!is_array($conf)) {
      return array();
    }
    $confExtracted = array();
    foreach ($this->keysReparent as $key => $parents) {
      $confExtracted[$key] = ConfUtil::confExtractNestedValue($conf, $parents);
      ConfUtil::confUnsetNestedValue($conf, $parents);
    }
    return $confExtracted + $conf;
  }

}
