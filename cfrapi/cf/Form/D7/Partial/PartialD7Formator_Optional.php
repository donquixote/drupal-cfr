<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Util\ConfUtil;

class PartialD7Formator_Optional implements PartialD7FormatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   * @param bool $required
   *
   * @return array
   */
  public function schemaConfGetD7Form(
    CfSchemaInterface $schema, $conf, $label, D7FormatorHelperInterface $helper, $required
  ) {

    if (!$schema instanceof CfSchema_OptionalInterface) {
      return $helper->unknownSchema();
    }

    $decoratedSchema = $schema->getDecorated();

    $form = $helper->schemaConfGetD7Form(
      $decoratedSchema, $conf, $label, FALSE);

    if (NULL !== $form) {
      // Use the native emptyness of the decorated schema.
      return $form;
    }

    if (!is_array($conf)) {
      $conf = [];
    }

    $form = [
      'enabled' => [
        '#title' => $label,
        '#type' => 'checkbox',
        '#default_value' => !empty($conf['enabled']),
      ],
      'options' => [
        '#type' => 'container',
        # '#tree' => TRUE,
        '#attributes' => ['class' => ['cfrapi-child-options']],
        'content' => $helper->schemaConfGetD7Form(
          $decoratedSchema, $conf, NULL),

        '#process' => [

          function(array $element) {

            if (isset($element['content'])) {
              $element['content']['#parents'] = $element['#parents'];
            }

            return $element;
          },
        ],
      ],
      '#after_build' => [

        function(array $element /*, array &$form_state */) {

          $element['options']['#states']['visible'] = [
            ':input[' . $element['enabled']['#name'] . ']' => ['checked' => TRUE],
          ];
        },

        // Clear out $conf['options'], if $conf['enabled'] is empty.
        function(array $element, array &$form_state) {

          $enabled = ConfUtil::confExtractNestedValue(
            $form_state['values'],
            $element['enabled']['#parents']);

          if (empty($enabled)) {
            ConfUtil::confUnsetNestedValue(
              $form_state['values'],
              $element['options']['#parents']);
          }
        },
      ],
    ];

    return $form;
  }
}
