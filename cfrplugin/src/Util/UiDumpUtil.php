<?php

namespace Drupal\cfrplugin\Util;

use Donquixote\Cf\Util\HtmlUtil;
use Drupal\cfrapi\Util\UtilBase;
use Drupal\Core\Render\Markup;

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
      HtmlUtil::sanitize($e->getMessage()),
    ];

    $rows[] = [
      t('Exception'),
      t(
        '@class thrown in line %line of @file',
        [
          '@class' => Markup::create(''
            . '<code>' . HtmlUtil::sanitize($e_class_reflection->getShortName()) . '</code>'
            . '<br/>'),
          '%line' => $e->getLine(),
          '@file' => Markup::create(''
            . '<code>' . HtmlUtil::sanitize(basename($file)) . '</code>'
            . '<br/>'
            . '<code>' . HtmlUtil::sanitize($file) . '</code>'),
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
   *
   * @todo Currently unused?
   */
  public static function exceptionGetHtml(\Exception $e) {
    $build = self::displayException($e);
    // @todo Make this a service, and inject the renderer.
    return \Drupal::service('renderer')->render($build);
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
            'Exception in line %line of %file',
            [
              '%line' => $e->getLine(),
              '%file' => basename($file)
            ]
          ) . '</dt>'
          . '  <dd><code>' . HtmlUtil::sanitize($file) . '</code></dd>'
          . '  <dt>'
          . t('Exception class: %class', ['%class' => $e_class_reflection->getShortName()])
          . '</dt>'
          . '  <dd>' . HtmlUtil::sanitize($e_class) . '</dt>'
          . '  <dt>' . t('Exception message:') . '</dt>'
          . '  <dd><pre>' . HtmlUtil::sanitize($e->getMessage()) . '</pre></dd>'
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

    if (function_exists('dpm')) {
      /** @var \Drupal\devel\DevelDumperManagerInterface $dumper */
      $dumper = \Drupal::service('devel.dumper');
      $element['dump'] = $dumper->exportAsRenderable($data);
    }
    else {
      $element['notice']['#markup'] = t('No dump utility available. Install devel.');
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
      return '<pre>' . var_export($v, TRUE) . '</pre>';
    }

    if (function_exists('kdevel_print_object')) {
      return kdevel_print_object($v);
    }

    if (is_object($v)) {
      return t(
        'Object of class @class.',
        ['@class' => Markup::create('<code>' . get_class($v) . '</code>')]
      );
    }

    return '<pre>' . var_export($v, TRUE) . '</pre>';
  }
}
