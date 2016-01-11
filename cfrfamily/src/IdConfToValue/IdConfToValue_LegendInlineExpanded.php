<?php

namespace Drupal\cfrfamily\IdConfToValue;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\CfrLegendItem\ParentLegendItemInterface;

class IdConfToValue_LegendInlineExpanded implements IdConfToValueInterface {

  /**
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $legend;

  /**
   * @var \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface
   */
  private $idConfToValue;

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $decoratedLegend
   * @param \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface $idConfToValue
   */
  function __construct(CfrLegendInterface $decoratedLegend, IdConfToValueInterface $idConfToValue) {
    $this->legend = $decoratedLegend;
    $this->idConfToValue = $idConfToValue;
  }

  /**
   * @param string|int $id
   * @param mixed $conf
   *
   * @return \Drupal\cfrapi\ConfToValue\ConfToValueInterface|null
   */
  function idConfGetValue($id, $conf) {

    if ($this->legend->idIsKnown($id)) {
      return $this->idConfToValue->idConfGetValue($id, $conf);
    }

    $pos = 0;
    while (FALSE !== $pos = strpos($id, '/', $pos + 1)) {
      $k = substr($id, 0, $pos);
      if (!$this->legend->idIsKnown($k)) {
        continue;
      }
      $outerLegendItem = $this->legend->idGetLegendItem($k);
      if (!$outerLegendItem instanceof ParentLegendItemInterface) {
        continue;
      }
      if (NULL === $inlineLegend = $outerLegendItem->getCfrLegend()) {
        continue;
      }
      if (!$inlineLegend instanceof IdConfToValueInterface) {
        continue;
      }
      $subId = substr($id, $pos + 1);
      return $inlineLegend->idConfGetValue($subId, $conf);
    }

    return new BrokenValue($this, get_defined_vars(), 'Unknown id.');
  }

}
