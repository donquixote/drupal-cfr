<?php

namespace Drupal\cfrfamily\IdConfLegend;

use Drupal\cfrapi\Legend\LegendInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

interface IdConfLegendInterface extends LegendInterface {

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return array|null
   */
  public function idConfGetOptionsForm($id, $optionsConf);

  /**
   * @param string $id
   * @param mixed $optionsConf
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return string|null
   */
  public function idConfGetSummary($id, $optionsConf, SummaryBuilderInterface $summaryBuilder);

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  public function idConfGetValue($id, $optionsConf);

}
