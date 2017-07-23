<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\StaUtil;

class D7FormatorP2_Optional implements D7FormatorP2Interface {

  /**
   * @var \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface
   */
  private $decorated;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface|null
   */
  public static function create(
    CfSchema_OptionalInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    if (NULL !== $emptyness = StaUtil::emptyness(
      $schema->getDecorated(),
      $schemaToAnything)
    ) {
      return StaUtil::formatorP2Optional(
        $schema->getDecorated(),
        $schemaToAnything
      );
    }

    $decorated = StaUtil::formatorP2(
      $schema->getDecorated(),
      $schemaToAnything);

    return new self($decorated);
  }

  /**
   * @param \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface $decorated
   */
  public function __construct(D7FormatorP2Interface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {

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
        'content' => $this->decorated->confGetD7Form($conf, NULL, $translator),
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
