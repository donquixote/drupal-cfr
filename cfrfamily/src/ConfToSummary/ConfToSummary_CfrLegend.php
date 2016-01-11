<?php

namespace Drupal\cfrfamily\ConfToSummary;

use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrapi\Util\ConfUtil;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;

class ConfToSummary_CfrLegend implements ConfToSummaryInterface {

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
   * @return \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface
   */
  static function createRequired(CfrLegendInterface $legend) {
    return new self($legend, TRUE);
  }

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   *
   * @return \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface
   */
  static function createOptional(CfrLegendInterface $legend) {
    return new self($legend, FALSE);
  }

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param bool $required
   */
  function __construct(CfrLegendInterface $legend, $required) {
    $this->legend = $legend;
    $this->required = $required;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf, $this->idKey, $this->optionsKey);

    if (NULL === $id) {
      if ($this->required) {
        return '- ' . t('Missing') . ' -';
      }
      else {
        return '- ' . t('None') . ' -';
      }
    }

    $legendItem = $this->legend->idGetLegendItem($id);

    if (NULL === $legendItem) {
      return '- ' . t('Unknown') . ' -';
    }

    $idLabel = $legendItem->getLabel();

    return $summaryBuilder->idConf($idLabel, $legendItem, $optionsConf);
  }
}
