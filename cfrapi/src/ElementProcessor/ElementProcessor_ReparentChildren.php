<?php

namespace Drupal\cfrapi\ElementProcessor;

use Drupal\Core\Form\FormStateInterface;

/**
 * To be used as a $form[*]['#process'][] callback.
 */
class ElementProcessor_ReparentChildren implements ElementProcessorInterface {

  /**
   * @var string[][]
   */
  private $keysReparent;

  /**
   * @param string[][] $keysReparent
   */
  public function __construct(array $keysReparent) {
    $this->keysReparent = $keysReparent;
  }

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function __invoke(array $element, FormStateInterface $form_state) {
    foreach ($this->keysReparent as $key => $parents) {
      if (isset($element[$key])) {
        $element[$key]['#parents'] = array_merge($element['#parents'], $parents);
      }
    }
    return $element;
  }

}
