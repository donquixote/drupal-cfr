<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;

class PartialD7Formator_Sequence implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   */
  public function __construct(CfSchema_SequenceInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {

    return [
      '#markup' => $helper->translate('Currently, sequences are not supported.'),
    ];
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array|null
   */
  public function _confGetForm($conf, $label, D7FormatorHelperInterface $helper) {

    if (!is_array($conf)) {
      $conf = [];
    }

    $_this = $this;

    if (NULL !== $label && '' !== $label && 0 !== $label) {
      $form = [
        '#type' => 'container',
        '#title' => $label,
      ];
    }
    else {
      $form = [
        '#type' => 'container',
      ];
    }

    $form['#attributes']['class'][] = 'cfrapi-child-options';

    $form += [
      '#input' => TRUE,
      '#default_value' => $conf,
      '#process' => [
        function (array $element /*, array &$form_state */) use ($_this, $helper) {
          return $_this->elementProcess(
            $element,
            $helper);
        },
      ],
      '#after_build' => [
        function (array $element, array &$form_state) use ($_this, $helper) {
          return $_this->elementAfterBuild(
            $element,
            $form_state,
            $helper);
        },
      ],
    ];

    return $form;
  }

  /**
   * @param array $element
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  private function elementProcess(array $element, D7FormatorHelperInterface $helper) {

    $conf = $element['#value'];

    if (!is_array($conf)) {
      $conf = [];
    }

    $itemSchema = $this->schema->getItemSchema();

    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Skip non-numeric and negative keys.
        continue;
      }

      // @todo Find another way, not using any "emptyness".
      list($itemEnabled, $itemConf) = [false, null];
         # = $helper->schemaConfGetStatusAndOptions($itemSchema, $itemConf);

      if (!$itemEnabled) {
        // Skip empty items.
        continue;
      }

      $element[$delta] = $helper->schemaConfGetD7Form(
        $this->schema->getItemSchema(), $itemConf, $this->deltaGetItemLabel($delta, $helper)
      );
    }

    // Element for new item.
    $element[] = $helper->schemaConfGetD7Form(
      $itemSchema, NULL, $this->deltaGetItemLabel(NULL, $helper)
    );

    return $element;
  }

  /**
   * @param int|null $delta
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return string
   */
  private function deltaGetItemLabel($delta, D7FormatorHelperInterface $helper) {
    return $this->schema->deltaGetItemLabel($delta, $helper);

    /*
    return (NULL === $delta)
      ? t('New item')
      : t('Item !n', ['!n' => '#' . check_plain($delta)]);
    */
  }

  /**
   * Callback for '#after_build' to clean up empty items in the form value.
   *
   * @param array $element
   * @param array $form_state
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  private function elementAfterBuild(
    array $element,
    array &$form_state,
    /** @noinspection PhpUnusedParameterInspection */ D7FormatorHelperInterface $helper)
  {
    $conf = drupal_array_get_nested_value($form_state['values'], $element['#parents']);

    if (!is_array($conf)) {
      $conf = [];
    }

    # $itemSchema = $this->schema->getItemSchema();

    foreach ($conf as $delta => $itemConf) {
      list($enabled) = [false, null];
        # = $helper->schemaConfGetStatusAndOptions($itemSchema, $itemConf);
      if (!$enabled) {
        unset($conf[$delta]);
      }
    }

    $conf = array_values($conf);

    drupal_array_set_nested_value($form_state['values'], $element['#parents'], $conf);

    if (isset($element['#title']) && '' !== $element['#title']) {
      $element['#theme_wrappers'][] = 'form_element';
    }

    return $element;
  }
}
