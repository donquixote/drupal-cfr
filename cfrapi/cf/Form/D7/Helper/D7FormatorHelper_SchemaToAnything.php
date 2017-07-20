<?php

namespace Donquixote\Cf\Form\D7\Helper;

use Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorDecoratorBase;
use Donquixote\Cf\Translator\TranslatorInterface;

class D7FormatorHelper_SchemaToAnything extends TranslatorDecoratorBase implements D7FormatorHelperInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(
    SchemaToAnythingInterface $schemaToAnything,
    TranslatorInterface $translator
  ) {
    $this->schemaToAnything = $schemaToAnything;
    parent::__construct($translator);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   */
  public function schemaConfGetD7Form(CfSchemaInterface $schema, $conf, $label) {

    # kdpm($schema, __METHOD__);

    $partial = $this->schemaToAnything->schema(
      $schema,
      PartialD7FormatorInterface::class);

    if (NULL === $partial) {
      # kdpm($schema, "PartialD7Formator for schema is NULL.");

      return $this->messageBuildBrokenForm(
        $this->translate(
          "Lacking !type support<br/>for schema !schema.",
          [
            '!type' => 'PartialD7Formator',
            '!schema' => $this->tValue($schema),
          ]));
    }

    # kdpm($partial, "Partial found.");

    if (!$partial instanceof PartialD7FormatorInterface) {
      # dpm("Partial has wrong type");

      return $this->messageBuildBrokenForm(
        $this->translate(
          'Misbehaving STA: Expected !expected, found !found, for schema !schema',
          [
            '!expected' => '<code>' . PartialD7FormatorInterface::class . '</code>',
            '!found' => $this->tValue($partial),
            '!schema' => $this->tValue($schema),
          ]));
    }

    $form = $partial->confGetD7Form($conf, $label, $this);

    # kdpm($form, "The form");

    return $form;
  }

  /**
   * @param string $message
   *
   * @return array
   */
  private function messageBuildBrokenForm($message) {

    return [
      '#type' => 'container',
      'message_box' => [
        '#type' => 'container',
        'message' => [
          '#markup' => $message,
        ],
      ],
    ];
  }

  /**
   * @param mixed $value
   * @param bool $em
   *
   * @return string
   */
  private function tValue($value, $em = TRUE) {

    if (is_object($value)) {
      $str = $this->translate('!class object', [
          '!class' => '<code>' . get_class($value) . '</code>',
        ]);
    }
    else {
      $str = $this->translate('!type value', [
        '!type' => gettype($value),
      ]);
    }

    if ($em) {
      # $str = '<em>' . $str . '</em>';
    }

    return $str;
  }
}
