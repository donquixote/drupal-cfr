<?php

namespace Drupal\cfrplugin\Util;

use Drupal\cfrapi\Util\UtilBase;

final class UiUtil extends UtilBase {

  /**
   * @param string $class
   *
   * @return string|null
   */
  public static function classGetPhp($class) {

    $reflectionClass = new \ReflectionClass($class);

    $filename = $reflectionClass->getFileName();
    if (FALSE === $filename || !is_readable($filename)) {
      return NULL;
    }

    return file_get_contents($filename);
  }

  /**
   * @param string $text
   *
   * @return string
   *
   * @see codefilter_process_php()
   */
  public static function highlightPhp($text) {
    // Note, pay attention to odd preg_replace-with-/e behaviour on slashes.
    // Undo possible linebreak filter conversion.
    $text = preg_replace('@</?(br|p)\s*/?>@', '', str_replace('\"', '"', $text));
    // Undo the escaping in the prepare step.
    $text = decode_entities($text);
    // Trim leading and trailing linebreaks.
    $text = trim($text, "\r\n");
    // Highlight as PHP.
    $text = '<div class="codeblock"><pre>' . highlight_string($text, TRUE) . '</pre></div>';

    // Remove newlines to avoid clashing with the linebreak filter.
    # $text = str_replace("\n", '', $text);

    // Fix spaces.
    $text = preg_replace('@&nbsp;(?!&nbsp;)@', ' ', $text);
    // A single space before text is ignored by browsers. If a single space
    // follows a break tag, replace it with a non-breaking space.
    $text = preg_replace('@<br /> ([^ ])@', '<br />&nbsp;$1', $text);

    return $text;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public static function interfaceNameIsValid($interface) {
    $fragment = DRUPAL_PHP_FUNCTION_PATTERN;
    $backslash = preg_quote('\\', '/');
    $regex = '/^' . $fragment . '(' . $backslash . $fragment . ')*$/';
    return 1 === preg_match($regex, $interface);
  }

}
