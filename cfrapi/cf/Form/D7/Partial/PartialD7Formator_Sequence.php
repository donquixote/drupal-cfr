<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;

class PartialD7Formator_Sequence implements PartialD7FormatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   * @param bool $required
   *
   * @return array|null
   */
  public function schemaConfGetD7Form(
    CfSchemaInterface $schema, $conf, $label, D7FormatorHelperInterface $helper, $required)
  {
    if (!$schema instanceof CfSchema_SequenceInterface) {
      return $helper->unknownSchema();
    }

    if (!is_array($conf)) {
      $conf = [];
    }

    if (!$required) {
      return NULL;
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
        function (array $element /*, array &$form_state */) use ($_this, $schema, $helper) {
          return $_this->elementProcess(
            $element,
            $schema,
            $helper);
        },
      ],
      '#after_build' => [
        function (array $element, array &$form_state) use ($_this, $schema, $helper) {
          return $_this->elementAfterBuild(
            $element,
            $form_state,
            $schema,
            $helper);
        },
      ],
    ];

    return $form;
  }

  /**
   * @param array $element
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $sequenceSchema
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  private function elementProcess(
    array $element,
    CfSchema_SequenceInterface $sequenceSchema,
    D7FormatorHelperInterface $helper)
  {
    $conf = $element['#value'];

    if (!is_array($conf)) {
      $conf = [];
    }

    $itemSchema = $sequenceSchema->getItemSchema();

    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Skip non-numeric and negative keys.
        continue;
      }

      list($itemEnabled, $itemConf) = $helper->schemaConfGetStatusAndOptions(
        $itemSchema, $itemConf);

      if (!$itemEnabled) {
        // Skip empty items.
        continue;
      }

      $element[$delta] = $helper->schemaConfGetD7Form(
        $sequenceSchema->getItemSchema(),
        $itemConf,
        $this->deltaGetItemLabel($delta, $sequenceSchema, $helper),
        FALSE);
    }

    // Element for new item.
    $element[] = $helper->schemaConfGetD7Form(
      $itemSchema,
      $helper->schemaGetEmptyConf(),
      $this->deltaGetItemLabel(NULL, $sequenceSchema, $helper),
      FALSE);

    return $element;
  }

  /**
   * @param int|null $delta
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $sequenceSchema
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return string
   */
  private function deltaGetItemLabel(
    $delta,
    CfSchema_SequenceInterface $sequenceSchema,
    D7FormatorHelperInterface $helper)
  {
    return $sequenceSchema->deltaGetItemLabel($delta, $helper);

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
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $sequenceSchema
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  private function elementAfterBuild(
    array $element,
    array &$form_state,
    CfSchema_SequenceInterface $sequenceSchema,
    D7FormatorHelperInterface $helper)
  {
    $conf = drupal_array_get_nested_value($form_state['values'], $element['#parents']);

    if (!is_array($conf)) {
      $conf = [];
    }

    $itemSchema = $sequenceSchema->getItemSchema();

    foreach ($conf as $delta => $itemConf) {
      list($enabled) = $helper->schemaConfGetStatusAndOptions($itemSchema, $itemConf);
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
