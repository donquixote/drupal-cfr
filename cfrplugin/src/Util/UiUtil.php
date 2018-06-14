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

    try {
      $reflectionClass = new \ReflectionClass($class);
    }
    catch (\ReflectionException $e) {
      return NULL;
    }

    $filename = $reflectionClass->getFileName();

    if (FALSE === $filename || !is_readable($filename)) {
      return NULL;
    }

    return file_get_contents($filename);
  }

  /**
   * @param string $php
   *
   * @return string
   *
   * @see codefilter_process_php()
   */
  public static function highlightPhp($php) {
    // Note, pay attention to odd preg_replace-with-/e behaviour on slashes.
    // Undo possible linebreak filter conversion.
    $text = preg_replace('@</?(br|p)\s*/?>@', '', str_replace('\"', '"', $php));
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

  /**
   * @param \Exception $e
   *
   * @return array
   */
  public static function displayException(\Exception $e) {

    $file = $e->getFile();
    $e_class = get_class($e);
    $e_reflection = new \ReflectionObject($e);

    return [
      'text' => [
        '#markup' => ''
          // @todo This should probably be in a template. One day.
          . '<dl>'
          . '  <dt>' . t('Exception in line %line of %file', ['%line' => $e->getLine(), '%file' => basename($file)]) . '</dt>'
          . '  <dd><code>' . check_plain($file) . '</code></dd>'
          . '  <dt>' . t('Exception class: %class', ['%class' => $e_reflection->getShortName()]) . '</dt>'
          . '  <dd>' . check_plain($e_class) . '</dt>'
          . '  <dt>' . t('Exception message:') . '</dt>'
          . '  <dd>' . check_plain($e->getMessage()) . '</dd>'
          . '</dl>',
      ],
      'trace_label' => [
        '#markup' => '<div>' . t('Exception stack trace') . ':</div>',
      ],
      'trace' => self::dumpData(BacktraceUtil::exceptionGetRelativeNicetrace($e)),
    ];
  }

  /**
   * @param mixed $data
   * @param string $fieldset_label
   *
   * @return array
   */
  public static function dumpDataInFieldset($data, $fieldset_label) {

    return self::dumpData($data)
      + [
        '#type' => 'fieldset',
        '#title' => $fieldset_label,
      ];
  }

  /**
   * @param mixed $data
   *
   * @return array
   */
  public static function dumpData($data) {

    $element = [];

    if (function_exists('krumong')) {
      $element['dump']['#markup'] = krumong()->dump($data);
    }
    elseif (function_exists('dpm')) {
      $element['dump']['#markup'] = krumo_ob($data);
      $element['notice']['#markup'] = '<p>' . t('Install krumong to see private and protected member variables.') . '</p>';
    }
    else {
      $element['notice']['#markup'] = t('No dump utility available. Install devel and/or krumong.');
    }

    return $element;
  }

}
