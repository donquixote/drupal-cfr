<?php

namespace Drupal\cfrplugin\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

class Form_IfaceDemo implements FormInterface {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'cfrplugin_iface_demo_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @param null $interface
   *
   * @return array The form structure.
   * The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $interface = NULL) {

    if (NULL === $interface) {
      throw new \RuntimeException("Interface demo form requires an interface as argument.");
    }

    $settings = isset($_GET['plugin'])
      ? $_GET['plugin']
      : [];

    $form['plugin'] = [
      '#type' => 'cfrplugin',
      '#cfrplugin_interface' => $interface,
      '#title' => t('Plugin'),
      '#default_value' => $settings,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Show'),
    ];

    return $form;
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
    // Nothing.
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

    $form_state->setRedirect(
      '<current>',
      ['plugin' => $form_state->getValue('plugin')]);
  }
}
