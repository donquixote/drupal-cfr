<?php

namespace Drupal\cfrapi\Util;

use Drupal\Core\Render\Markup;

/**
 * @method static string|\Drupal\Component\Render\MarkupInterface PRE(string $inner)
 */
class HtmlFilteredTagUtil {

  /**
   * @param string $tagName
   * @param array $arguments
   *
   * @return \Drupal\Component\Render\MarkupInterface|string
   */
  public static function __callStatic($tagName, array $arguments) {
    $innerHtml = $arguments[0];
    $outerHtml = '<' . $tagName . '>' . $innerHtml . '</' . $tagName . '>';
    return Markup::create($outerHtml);
  }

}
