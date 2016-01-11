<?php

namespace Drupal\cfrapi\Configurator\Composite;

use Drupal\cfrapi\BrokenValue\BrokenValueInterface;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\ConfiguratorFilter\ConfiguratorFilterInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\Util\ConfUtil;
use Drupal\cfrapi\ValueToValue\ValueToValueInterface;

class Configurator_ObjectSetterMethod implements ConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrapi\ConfiguratorFilter\ConfiguratorFilterInterface
   */
  private $filter;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $decorated
   * @param \Drupal\cfrapi\ConfiguratorFilter\ConfiguratorFilterInterface $filter
   */
  function __construct(ConfiguratorInterface $decorated, ConfiguratorFilterInterface $filter) {
    $this->decorated = $decorated;
    $this->filter = $filter;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetForm($conf, $label) {
    list($decoratedConf, $filterConf) = $this->confExtractOptions($conf);
    $form = array();
    $form['decorated'] = $this->decorated->confGetForm($decoratedConf, NULL);
    $form['filter'] = $this->filter->confGetForm($filterConf, NULL);
    return $form;
  }

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return mixed
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    list($decoratedConf, $filterConf) = $this->confExtractOptions($conf);
    return $summaryBuilder->startInline()
      ->addSetting($this->decorated, $decoratedConf)
      ->addSetting($this->filter, $filterConf)
      ->buildSummary();
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  function confGetValue($conf) {
    list($decoratedConf, $filterConf) = $this->confExtractOptions($conf);
    $value = $this->decorated->confGetValue($decoratedConf);
    if ($value instanceof BrokenValueInterface) {
      return $value;
    }
    $filter = $this->filter->confGetValue($filterConf);
    if ($filter instanceof BrokenValueInterface) {
      return $filter;
    }
    if ($filter instanceof ValueToValueInterface) {
      return $filter->valueGetValue($value);
    }
    return $value;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed[]
   */
  private function confExtractOptions($conf) {
    return ConfUtil::confExtractOptions($conf, array('decorated', 'filter'));
  }
}
