<?php

namespace Drupal\cfrapi\EnumMap;

class EnumMap implements EnumMapInterface {

  /**
   * @var mixed[]
   */
  private $groupedOptions;

  /**
   * @var string[]
   */
  private $optionLabels = [];

  /**
   * @param mixed[] $groupedOptions
   *   Select options with possible grouping.
   */
  public function __construct(array $groupedOptions) {

    // Validate $groupedOptions, and extract flat array.
    foreach ($groupedOptions as $groupOrKey => $groupOrLabel) {
      if (\is_array($groupOrLabel)) {
        // This is a group of options.
        foreach ($groupOrLabel as $key => $label) {
          if (is_numeric($label)) {
            $label = (string)$label;
          }
          elseif (!\is_string($label)) {
            throw new \InvalidArgumentException("Label for '$key' in '$groupOrKey' is not a string.");
          }
          $this->optionLabels[$key] = $label;
        }
      }
      else {
        // This is a single top-level option.
        if (is_numeric($groupOrLabel)) {
          $groupOrLabel = (string)$groupOrLabel;
        }
        elseif (!\is_string($groupOrLabel)) {
          throw new \InvalidArgumentException("Label for '$groupOrKey' is not a string.");
        }
        $this->optionLabels[$groupOrKey] = $groupOrLabel;
      }
    }

    $this->groupedOptions = $groupedOptions;
  }

  /**
   * @return mixed[]
   */
  public function getSelectOptions() {
    return $this->groupedOptions;
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    if (\is_int($id)) {
      $id = (string)$id;
    }
    elseif (NULL === $id || '' === $id) {
      return '- ' . t('Not specified') . ' -';
    }
    elseif (!\is_string($id)) {
      return '- ' . t('Invalid') . ' -';
    }
    if (!array_key_exists($id, $this->optionLabels)) {
      return '- ' . t('Unknown') . ' -';
    }
    return $this->optionLabels[$id];
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    if (!\is_string($id) && !\is_int($id)) {
      return FALSE;
    }
    return array_key_exists($id, $this->optionLabels);
  }
}
