<?php

namespace Drupal\cfrfamily\CfrLegend;

use Drupal\cfrfamily\CfrLegendItem\ParentLegendItemInterface;

class CfrLegend_InlineExpanded implements CfrLegendInterface {

  /**
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $decorated;

  /**
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $decorated
   */
  public function __construct(CfrLegendInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param int $depth
   *
   * @return \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface[]
   */
  public function getLegendItems($depth = 1) {
    $items = [];
    foreach ($this->decorated->getLegendItems() as $id => $item) {
      if ($depth > 0 && $item instanceof ParentLegendItemInterface && NULL !== $inlineLegend = $item->getCfrLegend()) {
        foreach ($inlineLegend->getLegendItems($depth - 1) as $inlineId => $inlineItem) {
          $items[$id . '/' . $inlineId] = $inlineItem->withLabels($inlineItem->getLabel(), $item->getLabel());
        }
        $items[$id] = $item->withLabels(t('more ..'), $item->getLabel());
      }
      else {
        $items[$id] = $item;
      }
    }

    # \Drupal\krumong\dpm($items);

    // @todo Prioritize first-level items on nameclash.
    return $items;
  }

  /**
   * @param string $combinedId
   *
   * @return \Drupal\cfrfamily\CfrLegendItem\CfrLegendItemInterface|null
   */
  public function idGetLegendItem($combinedId) {
    if ('' === $combinedId) {
      return NULL;
    }

    if ($this->decorated->idIsKnown($combinedId)) {
      return $this->decorated->idGetLegendItem($combinedId);
    }

    $pos = -1;
    while (FALSE !== $pos = strpos($combinedId, '/', $pos + 1)) {
      $k = substr($combinedId, 0, $pos);
      if (!$this->decorated->idIsKnown($k)) {
        continue;
      }
      $outerLegendItem = $this->decorated->idGetLegendItem($k);
      if (!$outerLegendItem instanceof ParentLegendItemInterface) {
        continue;
      }
      if (NULL === $inlineLegend = $outerLegendItem->getCfrLegend()) {
        continue;
      }
      $subId = substr($combinedId, $pos + 1);
      $innerLegendItem = $inlineLegend->idGetLegendItem($subId);
      if (NULL === $innerLegendItem) {
        continue;
      }
      return $innerLegendItem->withLabels($innerLegendItem->getLabel(), $outerLegendItem->getLabel());
    }

    return NULL;
  }

  /**
   * @param string $combinedId
   *
   * @return bool
   */
  public function idIsKnown($combinedId) {
    if ('' === $combinedId || NULL === $combinedId) {
      return FALSE;
    }

    if ($this->decorated->idIsKnown($combinedId)) {
      return TRUE;
    }

    $pos = -1;
    while (FALSE !== $pos = strpos($combinedId, '/', $pos + 1)) {
      $k = substr($combinedId, 0, $pos);
      if (!$this->decorated->idIsKnown($k)) {
        continue;
      }
      $outerLegendItem = $this->decorated->idGetLegendItem($k);
      if (!$outerLegendItem instanceof ParentLegendItemInterface) {
        continue;
      }
      if (NULL === $inlineLegend = $outerLegendItem->getCfrLegend()) {
        continue;
      }
      $subId = substr($combinedId, $pos + 1);
      if ($inlineLegend->idIsKnown($subId)) {
        return TRUE;
      }
    }

    return FALSE;
  }
}
