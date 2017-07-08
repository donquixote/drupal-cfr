<?php

namespace Drupal\cfrplugin\Util;

use Drupal\cfrapi\Util\UtilBase;

final class UiDumpUtil extends UtilBase {

  /**
   * @param \Exception $e
   *
   * @return string[][]
   */
  public static function exceptionGetTableRows(\Exception $e) {

    $file = $e->getFile();
    $e_class = get_class($e);
    $e_class_reflection = new \ReflectionClass($e_class);

    $rows = [];

    $rows[] = [
      t('Exception message'),
      check_plain($e->getMessage()),
    ];

    $rows[] = [
      t('Exception'),
      t(
        '!class thrown in line %line of !file',
        [
          '!class' => ''
            . '<code>' . check_plain($e_class_reflection->getShortName()) . '</code>'
            . '<br/>',
          '%line' => $e->getLine(),
          '!file' => ''
            . '<code>' . check_plain(basename($file)) . '</code>'
            . '<br/>'
            . '<code>' . check_plain($file) . '</code>',
        ]),
    ];

    $rows[] = [
      t('Stack trace'),
      self::dumpValue(BacktraceUtil::exceptionGetRelativeNicetrace($e)),
    ];

    return $rows;
  }

  /**
   * @param \Exception $e
   *
   * @return string
   */
  public static function exceptionGetHtml(\Exception $e) {
    $build = self::displayException($e);
    return drupal_render($build);
  }

  /**
   * @param \Exception $e
   *
   * @return array
   */
  public static function displayException(\Exception $e) {

    $file = $e->getFile();
    $e_class = get_class($e);
    $e_class_reflection = new \ReflectionClass($e_class);

    return [
      'text' => [
        '#markup' => ''
          // @todo This should probably be in a template. One day.
          . '<dl>'
          . '  <dt>' . t(
            'Exception in line %line of %file', [
            '%line' => $e->getLine(),
            '%file' => basename($file)
          ]
          ) . '</dt>'
          . '  <dd><code>' . check_plain($file) . '</code></dd>'
          . '  <dt>'
          . t('Exception class: %class', ['%class' => $e_class_reflection->getShortName()])
          . '</dt>'
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
      $element['notice']['#markup'] = '<p>'
        . t('Install krumong to see private and protected member variables.')
        . '</p>';
    }
    else {
      $element['notice']['#markup'] = t('No dump utility available. Install devel and/or krumong.');
    }

    return $element;
  }

  /**
   * @param mixed $v
   *
   * @return null|string
   */
  public static function dumpValue($v) {

    if (!is_object($v) && !is_array($v)) {
      return '<pre>' . var_export($v) . '</pre>';
    }

    if (function_exists('Drupal\krumong\dpm')) {
      return krumong()->dump($v);
    }

    if (function_exists('kdevel_print_object')) {
      return kdevel_print_object($v);
    }

    if (is_object($v)) {
      return t(
        'Object of class !class.',
        ['!class' => '<code>' . get_class($v) . '</code>']
      );
    }

    return '<pre>' . var_export($v, TRUE) . '</pre>';
  }
}
