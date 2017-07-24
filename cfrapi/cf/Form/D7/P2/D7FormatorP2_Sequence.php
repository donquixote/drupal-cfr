<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\ConfUtil;

class D7FormatorP2_Sequence implements D7FormatorP2Interface {

  /**
   * @var \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface
   */
  private $itemFormator;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface|null
   */
  public static function create(
    CfSchema_SequenceInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    $formator = D7FormatorP2_SequenceWithEmptiness::createOrNull(
      $schema,
      $schemaToAnything);

    if (NULL !== $formator) {
      return $formator;
    }

    return new D7FormatorP2_Broken(
      t("Sequences without emptiness are currently not supported."));
  }

  /**
   * @param \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface $itemFormator
   */
  public function __construct(D7FormatorP2Interface $itemFormator) {
    $this->itemFormator = $itemFormator;
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

    if ([] === $conf) {
      $conf = [NULL];
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
      # '#tree' => TRUE,
      '#input' => TRUE,
      '#default_value' => $conf,
      '#_value_callback' => function (array $element, $input, array &$form_state) use ($_this) {
        return $_this->elementValue($element, $input, $form_state);
      },
      '#process' => [
        function (array $element, array &$form_state, array $form) use ($_this, $translator) {
          return $_this->elementProcess(
            $element,
            $form_state,
            $form,
            $translator);
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
   * @param mixed|false $input
   * @param array $form_state
   *
   * @return array
   */
  private function elementValue(
    array $element,
    $input,
    /** @noinspection PhpUnusedParameterInspection */ array &$form_state
  ) {

    if (FALSE === $input) {
      return isset($element['#default_value'])
        ? $element['#default_value']
        : NULL;
    }

    return $input;
  }

  /**
   * @param array $element
   * @param array $form_state
   * @param array $form
   * @param \Donquixote\Cf\Translator\TranslatorInterface $helper
   *
   * @return array
   */
  private function elementProcess(array $element, array &$form_state, array $form, TranslatorInterface $helper) {

    $form_build_id = $form['form_build_id']['#value'];
    $elementId = sha1($form_build_id . serialize($element['#parents']));

    # $element['#attributes']['id'] = $uniqid;

    $conf = $element['#value'];
    # kdpm($element, __METHOD__);

    # $cconf = ConfUtil::confExtractNestedValue($form_state['values'], $element['#parents']);
    # kdpm(get_defined_vars(), __METHOD__);

    if (!is_array($conf)) {
      $conf = [];
    }

    if (isset($form_state['triggering_element']['#parents'])) {
      $triggering_element_parents = $form_state['triggering_element']['#parents'];
      $triggering_element_parents_expected = array_merge($element['#parents'], ['addmore']);
      if ($triggering_element_parents_expected === $triggering_element_parents) {
        // The 'addmore' was clicked. Add another item.
        $conf[] = NULL;
      }
      dpm(implode(' / ', $triggering_element_parents), 'TRIGGERING ELEMENT');
    }

    # $_this = $this;

    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Skip non-numeric and negative keys.
        continue;
      }

      $itemId = $elementId . '-' . $delta;

      $itemElement = $this->itemFormator->confGetD7Form(
        $itemConf,
        $this->deltaGetItemLabel($delta, $helper),
        $helper);

      $itemElement['#parents'] = array_merge($element['#parents'], [$delta]);

      $element[$delta] = [
        '#type' => 'container',
        '#attributes' => ['id' => $itemId],
        'item' => $itemElement,
        'remove' => [
          '#name' => implode('-', $element['#parents']) . '-' . $delta . '-remove',
          '#type' => 'submit',
          '#value' =>  t('Remove'),
          '#submit' => [
            // See https://api.drupal.org/api/examples/ajax_example%21ajax_example_graceful_degradation.inc/function/ajax_example_add_more_add_one/7.x-1.x
            function (
              /** @noinspection PhpUnusedParameterInspection */ array $form,
              array &$form_state
            ) {
              $button = $form_state['triggering_element'];
              $parents = array_slice($button['#array_parents'], 0, -1);
              # $delta = end($parents);
              $conf = ConfUtil::confExtractNestedValue($form_state['values'], $parents);
              dpm(get_defined_vars(), 'CLOSURE: remove #submit');
              # kdpm($conf, '$conf BEFORE');
              # kdpm($form_state['values'], '$form_state[values] BEFORE');
              ConfUtil::confUnsetNestedValue($form_state['values'], $parents);
              ConfUtil::confUnsetNestedValue($form_state['input'], $parents);
              # kdpm($conf, '$conf AFTER');
              # kdpm($form_state['values'], '$form_state[values] AFTER');
              # kdpm($button, '$button');
              $form_state['rebuild'] = TRUE;
            },
          ],
          '#limit_validation_errors' => [$element['#parents']],
          '#ajax' => [
            'wrapper' => $itemId,
            # 'effect' => 'fade',
            # 'method' => 'replace',
            'method' => 'remove',
            'progress' => [
              'type' => 'throbber',
              'message' => NULL,
            ],
            'effect' => 'none',
            // See https://api.drupal.org/api/examples/ajax_example%21ajax_example_graceful_degradation.inc/function/ajax_example_add_more_callback/7.x-1.x
            'callback' => function(array $form, array $form_state) use ($itemId) {
              dpm('CLOSURE: remove #ajax callback');

              return [
                '#type' => 'ajax',
                '#commands' => [
                  ajax_command_remove('#' . $itemId),
                ],
              ];
            },
            'callback_' => function(array $form, array $form_state) use ($conf) {

              dpm($conf, 'CONF');
              end($conf);
              $new_item_delta = key($conf);

              $button = $form_state['triggering_element'];

              // Go one level up in the form, to the sequence element.
              $element = drupal_array_get_nested_value(
                $form,
                array_slice($button['#array_parents'], 0, -1));

              # kdpm($element);

              # $element['x']['#markup'] = '<div class="ajax-new-content">X</div>';

              # $element

              $element = $element[$new_item_delta];

              return $element;
            },
          ],
        ],
      ];
    }

    # kdpm($element, __METHOD__ . ' FINISHED ELEMENT');

    $addmore = [
      '#parents' => array_merge($element['#parents'], ['addmore']),
      # '#tree' => TRUE,
      '#type' => 'button',
      '#value' =>  t('Add item'),
      '#weight' => 10,
      '#submit' => [
        // See https://api.drupal.org/api/examples/ajax_example%21ajax_example_graceful_degradation.inc/function/ajax_example_add_more_add_one/7.x-1.x
        function (array $form, array &$form_state) {
          dpm('CLOSURE: addmore #submit');
          $button = $form_state['triggering_element'];
          $parents = array_slice($button['#parents'], 0, -1);
          array_pop($parents);
          $conf = ConfUtil::confExtractNestedValue($form_state['values'], $parents);
          # kdpm($conf, '$conf BEFORE');
          # kdpm($form_state['values'], '$form_state[values] BEFORE');
          $conf[] = NULL;
          ConfUtil::confSetNestedValue($form_state['values'], $parents, $conf);
          # kdpm($conf, '$conf AFTER');
          # kdpm($form_state['values'], '$form_state[values] AFTER');
          # kdpm($button, '$button');
          $form_state['rebuild'] = TRUE;
        },
      ],
      '#limit_validation_errors' => [],
      '#ajax' => [
        // See https://api.drupal.org/api/examples/ajax_example%21ajax_example_graceful_degradation.inc/function/ajax_example_add_more_callback/7.x-1.x
        'callback' => function(array $form, array $form_state) use ($conf) {
          dpm('CLOSURE: addmore #ajax callback');
          dpm($conf, 'CONF');

          end($conf);
          $new_item_delta = key($conf);

          $button = $form_state['triggering_element'];

          // Go one level up in the form, to the sequence element.
          $element = drupal_array_get_nested_value(
            $form,
            array_slice($button['#array_parents'], 0, -1));

          # kdpm($element);

          # $element['x']['#markup'] = '<div class="ajax-new-content">X</div>';

          # $element

          $element = $element[$new_item_delta];

          return $element;
        },
        'wrapper' => $elementId,
        # 'effect' => 'fade',
        # 'method' => 'replace',
        'method' => 'before',
      ],
    ];

    $element['replaceme'] = [
      '#weight' => 9,
      # 'addmore' => $addmore,
      '#markup' => '<div id="' . $elementId . '"></div>',
    ];

    $element['addmore'] = $addmore;

    return $element;
  }

  /**
   * @param int|null $delta
   * @param \Donquixote\Cf\Translator\TranslatorInterface $helper
   *
   * @return string
   */
  private function deltaGetItemLabel($delta, TranslatorInterface $helper) {
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
   * @param \Donquixote\Cf\Translator\TranslatorInterface $helper
   *
   * @return array
   */
  private function elementAfterBuild(
    array $element,
    array &$form_state,
    /** @noinspection PhpUnusedParameterInspection */ TranslatorInterface $helper)
  {
    $conf = drupal_array_get_nested_value($form_state['values'], $element['#parents']);

    if (!is_array($conf)) {
      $conf = [];
    }

    # $itemSchema = $this->schema->getItemSchema();

    $enabled = false;
    foreach ($conf as $delta => $itemConf) {
      # list($enabled) = $helper->schemaConfGetStatusAndOptions($itemSchema, $itemConf);
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
