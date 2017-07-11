<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface;
use Donquixote\Cf\Util\ConfUtil;

class PartialD7Formator_Optional implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface $schemaToEmptyness
   *
   * @return \Closure
   */
  public static function getFactory(SchemaToEmptynessInterface $schemaToEmptyness) {

    /**
     * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
     *
     * @return \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface|null
     */
    return function(CfSchema_OptionalInterface $schema) use ($schemaToEmptyness) {

      $decoratedSchema = $schema->getDecorated();

      if (NULL === $emptyness = $schemaToEmptyness->schemaGetEmptyness($decoratedSchema)) {
        return new self($schema);
      }

      return self::createWithEmptyness($decoratedSchema, $emptyness);
    };
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decoratedSchema
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $emptyness
   *
   * @return \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface|null
   */
  private static function createWithEmptyness(
    CfSchemaInterface $decoratedSchema,
    EmptynessInterface $emptyness
  ) {

    if ($decoratedSchema instanceof CfSchema_DrilldownInterface) {
      return new PartialD7Formator_OptionalDrilldown($decoratedSchema);
    }

    if ($decoratedSchema instanceof CfSchema_OptionsInterface) {
      return new PartialD7Formator_OptionalOptions($decoratedSchema);
    }

    if ($decoratedSchema instanceof CfSchema_NeutralInterface) {
      return self::createWithEmptyness($decoratedSchema, $emptyness);
    }

    if ($decoratedSchema instanceof CfSchema_ValueToValueInterface) {
      return self::createWithEmptyness($decoratedSchema, $emptyness);
    }

    if ($decoratedSchema instanceof CfSchema_OptionlessInterface) {
      return new PartialD7Formator_Bool();
    }

    // @todo Do something! An emptyness-rewrite thingie.
    return NULL;
  }

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   */
  public function __construct(CfSchema_OptionalInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {

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
          $this->schema->getDecorated(),
          $conf,
          NULL
        ),

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
