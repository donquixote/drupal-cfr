<?php

namespace Donquixote\Cf\Markup;

use Donquixote\Cf\ATA\ATAInterface;
use Donquixote\Cf\Translatable\TranslatableInterface;
use Donquixote\Cf\Translator\TranslatorInterface;

class Markup_FromTranslatable {

  /**
   * @ATA
   *
   * @param \Donquixote\Cf\Translatable\TranslatableInterface $translatable
   * @param \Donquixote\Cf\ATA\ATAInterface $ata
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return \Donquixote\Cf\Markup\MarkupInterface|null
   */
  public static function create(
    TranslatableInterface $translatable,
    ATAInterface $ata,
    TranslatorInterface $translator
  ) {

    $replacements = [];
    foreach ($translatable->getReplacements() as $k => $v) {

      if (is_object($v)) {
        $replacement = $ata->cast($v, MarkupInterface::class);
        if (!$replacement instanceof MarkupInterface) {
          return NULL;
        }
      }
      elseif (is_string($v)) {
        $replacement = $v;
      }
      else {
        return NULL;
      }

      $replacements[$k] = $replacement;
    }

    $html = $translator->translate(
      $translatable->getOriginalText(),
      $replacements);

    return new Markup($html);
  }

}
