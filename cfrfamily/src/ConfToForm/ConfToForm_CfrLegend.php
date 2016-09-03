<?php

namespace Drupal\cfrfamily\ConfToForm;

use Drupal\cfrapi\ConfToForm\ConfToFormInterface;
use Drupal\cfrapi\Util\ConfUtil;
use Drupal\cfrapi\Util\FormUtil;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface;

class ConfToForm_CfrLegend implements ConfToFormInterface {

  /**
   * @var string|null
   */
  private $idLabel;

  /**
   * @var string
   */
  private $idKey = 'id';

  /**
   * @var string
   */
  private $optionsKey = 'options';

  /**
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $legend;

  /**
   * @var bool
   */
  private $required;

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   *
   * @return \Drupal\cfrapi\ConfToForm\ConfToFormInterface
   */
  public static function createRequired(CfrLegendInterface $legend) {
    return new self($legend, TRUE);
  }

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   *
   * @return \Drupal\cfrapi\ConfToForm\ConfToFormInterface
   */
  public static function createOptional(CfrLegendInterface $legend) {
    return new self($legend, FALSE);
  }

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param bool $required
   */
  public function __construct(CfrLegendInterface $legend, $required) {
    $this->legend = $legend;
    $this->required = $required;
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  public function getCfrLegend() {
    return $this->legend;
  }

  /**
   * @param array $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf, $this->idKey, $this->optionsKey);

    $form = [
      '#tree' => TRUE,
    ];

    if (!$this->legend->idIsKnown($id)) {
      $id = NULL;
    }

    $form[$this->idKey] = [
      '#title' => isset($label) ? $label : $this->idLabel,
      '#type' => 'select',
      '#options' => $this->getSelectOptions(),
      '#default_value' => $id,
    ];

    if ($this->required) {
      $form[$this->idKey]['#required'] = TRUE;
    }
    else {
      $form[$this->idKey]['#empty_value'] = '';
    }

    $optionsForm = [];
    if (NULL !== $id && NULL !== $legendItem = $this->legend->idGetLegendItem($id)) {
      $optionsForm = $legendItem->confGetForm($optionsConf, NULL);

      if (element_children($optionsForm)) {

        $optionsForm['#title'] = $this->legendItemGetOptionsLabel($legendItem);
        $optionsForm['#attributes']['class'][] = 'cfrapi-child-options';
        $optionsForm['#type'] = 'fieldset';

        // @todo Unfortunately, #collapsible fieldsets do not play nice with Views UI.
        // See https://www.drupal.org/node/2624020
        # $options_form['#collapsed'] = TRUE;
        # $options_form['#collapsible'] = TRUE;
      }
    }
    $form[$this->optionsKey] = $optionsForm;

    FormUtil::onProcessBuildDependency($form);

    return $form;
  }

  /**
   * @return mixed[]|string[]|string[][]
   */
  private function getSelectOptions() {

    $options = [];
    foreach ($this->legend->getLegendItems() as $id => $legendItem) {
      $label = $legendItem->getLabel();
      if (NULL !== $groupLabel = $legendItem->getGroupLabel()) {
        $options[$groupLabel][$id] = $label;
      }
      else {
        $options[$id] = $label;
      }
    }

    return $options;
  }

  /**
   * @param \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface $legendItem
   *
   * @return string
   */
  private function legendItemGetOptionsLabel(CfrLegendItemInterface $legendItem) {
    $idLabel = $legendItem->getLabel();
    return empty($idLabel)
      ? t('Options')
      : t('Options for "@name"', ['@name' => $idLabel]);
  }
}
