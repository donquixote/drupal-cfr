<?php

namespace Drupal\cfrplugin\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

class Form_GenericRedirectGET implements FormInterface {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'cfrplugin_generic_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param array|null $form_arg
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, array $form_arg = NULL) {

    if (NULL === $form_arg) {
      throw new \RuntimeException("Generic redirect form requires an argument.");
    }

    if (!isset($form_arg['#query_keys'])) {
      throw new \RuntimeException("Generic redirect form requires #query_keys.");
    }

    return $form_arg;
  }

  /**
   * Form validation handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Do nothing.
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    /** @noinspection ReferenceMismatchInspection */
    $values = $form_state->getValues();

    $query = [];
    foreach ($form['#query_keys'] as $key) {
      if (isset($values[$key])) {
        $query[$key] = $values[$key];
      }
    }


    $form_state->setRedirect(
      '<current>',
      $query);
  }
}
