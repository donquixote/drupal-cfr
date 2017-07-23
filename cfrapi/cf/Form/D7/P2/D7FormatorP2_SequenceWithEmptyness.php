<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\StaUtil;

class D7FormatorP2_SequenceWithEmptyness implements D7FormatorP2Interface {

  /**
   * @var \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  private $sequenceSchema;

  /**
   * @var \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface
   */
  private $optionalItemFormator;

  /**
   * @var \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  private $itemEmptyness;

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function createOrNull(
    CfSchema_SequenceInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    if (NULL === $emptyness = StaUtil::emptyness(
      $schema->getItemSchema(),
      $schemaToAnything)
    ) {
      kdpm($schema->getItemSchema(), 'no emptyness found.');
      return NULL;
    }

    $optionalFormator = StaUtil::formatorP2Optional(
      $schema->getItemSchema(),
      $schemaToAnything);

    if (NULL === $optionalFormator) {
      kdpm('Sorry.');
      return NULL;
    }

    return new self(
      $schema,
      $optionalFormator,
      $emptyness);
  }

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $sequenceSchema
   * @param \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface $optionalItemFormator
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $itemEmptyness
   */
  public function __construct(
    CfSchema_SequenceInterface $sequenceSchema,
    D7FormatorP2Interface $optionalItemFormator,
    EmptynessInterface $itemEmptyness
  ) {
    $this->sequenceSchema = $sequenceSchema;
    $this->optionalItemFormator = $optionalItemFormator;
    $this->itemEmptyness = $itemEmptyness;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array|null
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {

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
        function (array $element /*, array &$form_state */) use ($_this, $translator) {
          return $_this->elementProcess($element, $translator);
        },
      ],
      '#after_build' => [
        function (array $element, array &$form_state) use ($_this, $translator) {
          return $_this->elementAfterBuild(
            $element,
            $form_state,
            $translator);
        },
      ],
    ];

    return $form;
  }

  /**
   * @param array $element
   * @param \Donquixote\Cf\Translator\TranslatorInterface $helper
   *
   * @return array
   */
  private function elementProcess(array $element, TranslatorInterface $helper) {

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
   * @param \Donquixote\Cf\Translator\TranslatorInterface $helper
   *
   * @return string
   */
  private function deltaGetItemLabel($delta, TranslatorInterface $helper) {
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
   * @param \Donquixote\Cf\Translator\TranslatorInterface $helper
   *
   * @return array
   */
  private function elementAfterBuild(
    array $element,
    array &$form_state,
    /** @noinspection PhpUnusedParameterInspection */ TranslatorInterface $helper)
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
