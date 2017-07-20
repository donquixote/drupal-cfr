<?php

namespace Donquixote\Cf\Util;

class HtmlUtil {

  /**
   * @param string $text
   *
   * @return string
   */
  public static function sanitize($text) {
    return htmlspecialchars($text, ENT_QUOTES);
  }

}
