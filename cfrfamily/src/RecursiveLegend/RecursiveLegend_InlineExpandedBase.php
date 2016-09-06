<?php

namespace Drupal\cfrfamily\RecursiveLegend;

use Drupal\cfrapi\Legend\LegendInterface;

abstract class RecursiveLegend_InlineExpandedBase implements RecursiveLegendInterface {

  /**
   * @var \Drupal\cfrapi\Legend\LegendInterface|\Drupal\cfrfamily\RecursiveLegend\RecursiveLegendInterface
   */
  private $decorated;

  /**
   * @param \Drupal\cfrapi\Legend\LegendInterface $decorated
   */
  public function __construct(LegendInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string|mixed $combinedId
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
      $idPrefix = substr($combinedId, 0, $pos);
      if (NULL !== $inlineLegend = $this->idGetInlineLegend($idPrefix)) {
        if ($this->idIsKnown($idPrefix)) {
          $idSuffix = substr($combinedId, $pos + 1);
          if ($inlineLegend->idIsKnown($idSuffix)) {
            return TRUE;
          }
        }
      }
    }

    return FALSE;
  }

  /**
   * @param int $maxRecursionDepth
   *
   * @return mixed[]|string[]|string[][]
   */
  public function getSelectOptions($maxRecursionDepth = 1) {

    if ($maxRecursionDepth < 1) {
      return $this->decorated->getSelectOptions($maxRecursionDepth);
    }

    $options = [];
    foreach ($this->decorated->getSelectOptions($maxRecursionDepth) as $idOrGroupLabel => $labelOrGroupOptions) {
      if (!is_array($labelOrGroupOptions)) {
        $options[$idOrGroupLabel] = $labelOrGroupOptions;
        if ([] !== $inlineOptions = $this->idGetInlineOptions($idOrGroupLabel, $maxRecursionDepth)) {
          if (!isset($options[$labelOrGroupOptions])) {
            $options[$labelOrGroupOptions] = $inlineOptions;
          }
          elseif (is_array($options[$labelOrGroupOptions])) {
            $options[$labelOrGroupOptions] += $inlineOptions;
          }
        }
      }
      else {
        foreach ($labelOrGroupOptions as $id => $label) {
          $options[$idOrGroupLabel][$id] = $label;
          if ([] !== $inlineOptions = $this->idGetInlineOptions($id, $maxRecursionDepth)) {
            if (!isset($options[$label])) {
              $options[$label] = $inlineOptions;
            }
            elseif (is_array($options[$label])) {
              $options[$label] += $inlineOptions;
            }
          }
        }
      }
    }

    return $options;
  }

  /**
   * @param string $idPrefix
   * @param int $maxRecursionDepth
   *
   * @return string[]
   */
  private function idGetInlineOptions($idPrefix, $maxRecursionDepth) {

    if (NULL === $inlineLegend = $this->idGetInlineLegend($idPrefix)) {
      return [];
    }

    $options = [];
    foreach ($inlineLegend->getSelectOptions($maxRecursionDepth - 1) as $idOrGroupLabel => $labelOrGroupOptions) {
      if (!is_array($labelOrGroupOptions)) {
        $options[$idPrefix . '/' . $idOrGroupLabel] = $labelOrGroupOptions;
      }
      else {
        foreach ($labelOrGroupOptions as $id => $label) {
          $options[$idPrefix . '/' . $id] = $label;
        }
      }
    }

    return $options;
  }

  /**
   * @param string|mixed $combinedId
   *
   * @return string|null
   */
  public function idGetLabel($combinedId) {

    if ('' === $combinedId || NULL === $combinedId) {
      return NULL;
    }

    if (NULL !== $label = $this->decorated->idGetLabel($combinedId)) {
      return $label;
    }

    $pos = -1;
    while (FALSE !== $pos = strpos($combinedId, '/', $pos + 1)) {
      $idPrefix = substr($combinedId, 0, $pos);
      if (NULL !== $inlineLegend = $this->idGetInlineLegend($idPrefix)) {
        if (NULL !== $labelPrefix = $this->decorated->idGetLabel($idPrefix)) {
          $idSuffix = substr($combinedId, $pos + 1);
          if (NULL !== $label = $inlineLegend->idGetLabel($idSuffix)) {
            return $label;
          }
        }
      }
    }

    return FALSE;
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cfrfamily\RecursiveLegend\RecursiveLegendInterface|null
   */
  abstract protected function idGetInlineLegend($id);
}
