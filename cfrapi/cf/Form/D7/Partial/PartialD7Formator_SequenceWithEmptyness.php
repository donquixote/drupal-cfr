<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Form\D7\Optional\PartialD7FormatorOptionalInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class PartialD7Formator_SequenceWithEmptyness implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  private $sequenceSchema;

  /**
   * @var \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface
   */
  private $optionalItemFormator;

  /**
   * @var \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  private $itemEmptyness;

  /**
   *
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function createOrNull(
    CfSchema_SequenceInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    $emptyness = $schemaToAnything->schema(
      $schema->getItemSchema(),
      EmptynessInterface::class);

    if (NULL === $emptyness) {
      kdpm($schema->getItemSchema(), 'no emptyness found.');
      return NULL;
    }

    $formatorOptional = $schemaToAnything->schema(
      $schema->getItemSchema(),
      PartialD7FormatorOptionalInterface::class);

    if (NULL === $formatorOptional || !$formatorOptional instanceof PartialD7FormatorOptionalInterface) {
      kdpm('Sorry.');
      return NULL;
    }

    $optionalFormator = $formatorOptional->getFormator();

    return new self(
      $schema,
      $optionalFormator,
      $emptyness);
  }

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $sequenceSchema
   * @param \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface $optionalItemFormator
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $itemEmptyness
   */
  public function __construct(
    CfSchema_SequenceInterface $sequenceSchema,
    PartialD7FormatorInterface $optionalItemFormator,
    EmptynessInterface $itemEmptyness
  ) {
    $this->sequenceSchema = $sequenceSchema;
    $this->optionalItemFormator = $optionalItemFormator;
    $this->itemEmptyness = $itemEmptyness;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {

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
          return $_this->elementProcess($element, $helper);
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

    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Skip non-numeric and negative keys.
        continue;
      }

      if ($this->itemEmptyness->confIsEmpty($itemConf)) {
        // Skip empty items.
        continue;
      }

      $itemLabel = $this->deltaGetItemLabel($delta, $helper);

      $element[$delta] = $this->optionalItemFormator->confGetD7Form(
        $itemConf,
        $itemLabel,
        $helper);
    }

    $newItemLabel = $this->deltaGetItemLabel(NULL, $helper);

    // Element for new item.
    $element[] = $this->optionalItemFormator->confGetD7Form(
      NULL,
      $newItemLabel,
      $helper);

    return $element;
  }

  /**
   * @param int|null $delta
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return string
   */
  private function deltaGetItemLabel($delta, D7FormatorHelperInterface $helper) {
    return $this->sequenceSchema->deltaGetItemLabel($delta, $helper);

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

    $conf = drupal_array_get_nested_value(
      $form_state['values'],
      $element['#parents']);

    if (!is_array($conf)) {
      $conf = [];
    }

    # $itemSchema = $this->schema->getItemSchema();

    foreach ($conf as $delta => $itemConf) {
      if ($this->itemEmptyness->confIsEmpty($itemConf)) {
        unset($conf[$delta]);
      }
    }

    $conf = array_values($conf);

    drupal_array_set_nested_value(
      $form_state['values'],
      $element['#parents'],
      $conf);

    if (isset($element['#title']) && '' !== $element['#title']) {
      $element['#theme_wrappers'][] = 'form_element';
    }

    return $element;
  }
}
