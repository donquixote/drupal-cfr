<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Form\D7\Optional\PartialD7FormatorOptionalInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface;
use Donquixote\Cf\Util\ConfUtil;

class PartialD7Formator_Optional implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private $decoratedSchema;

  /**
   * @Cf
   *
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

      return self::createFromRequiredSchema(
        $schema->getDecorated(),
        $schemaToEmptyness);
    };
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface|null
   */
  public static function create(
    CfSchema_OptionalInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    kdpm(__METHOD__);

    $emptyness = $schemaToAnything->schema(
      $schema->getDecorated(),
      EmptynessInterface::class);

    if (NULL === $emptyness || !$emptyness instanceof EmptynessInterface) {
      return new self($schema->getDecorated());
    }

    $formatorOptional = $schemaToAnything->schema(
      $schema->getDecorated(),
      PartialD7FormatorOptionalInterface::class);

    if (NULL === $formatorOptional || !$formatorOptional instanceof PartialD7FormatorOptionalInterface) {
      kdpm('Sorry.');
      return NULL;
    }

    kdpm($formatorOptional, '$formatorOptional.');

    return $formatorOptional->getFormator();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decoratedSchema
   * @param \Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface $schemaToEmptyness
   *
   * @return \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface|null
   */
  public static function createFromRequiredSchema(
    CfSchemaInterface $decoratedSchema,
    SchemaToEmptynessInterface $schemaToEmptyness
  ) {

    if (NULL === $emptyness = $schemaToEmptyness->schemaGetEmptyness($decoratedSchema)) {
      return new self($decoratedSchema);
    }

    return self::createWithEmptyness($decoratedSchema, $emptyness);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decoratedSchema
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $emptyness
   *
   * @return \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface|null
   */
  public static function createWithEmptyness(
    CfSchemaInterface $decoratedSchema,
    EmptynessInterface $emptyness
  ) {

    if ($decoratedSchema instanceof CfSchema_DrilldownInterface) {
      return new PartialD7Formator_OptionalDrilldown($decoratedSchema);
    }

    if ($decoratedSchema instanceof CfSchema_OptionsInterface) {
      return new PartialD7Formator_OptionalOptions($decoratedSchema);
    }

    if ($decoratedSchema instanceof CfSchema_ValueToValueBaseInterface) {
      return self::createWithEmptyness(
        $decoratedSchema->getDecorated(),
        $emptyness);
    }

    if ($decoratedSchema instanceof CfSchema_OptionlessInterface) {
      return new PartialD7Formator_Bool();
    }

    // @todo Do something! An emptyness-rewrite thingie.
    kdpm(get_defined_vars(), __METHOD__);
    return NULL;
  }

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $optionalSchema
   *
   * @return \Donquixote\Cf\Form\D7\Partial\PartialD7Formator_Optional
   */
  public static function createWithoutEmptyness(CfSchema_OptionalInterface $optionalSchema) {
    return new self($optionalSchema->getDecorated());
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decoratedSchema
   */
  public function __construct(CfSchemaInterface $decoratedSchema) {
    $this->decoratedSchema = $decoratedSchema;
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
      '#tree' => TRUE,
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
          $this->decoratedSchema,
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

          return $element;
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

          return $element;
        },
      ],
    ];

    return $form;
  }
}
