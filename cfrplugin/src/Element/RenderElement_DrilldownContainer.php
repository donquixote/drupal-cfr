<?php

namespace Drupal\cfrplugin\Element;

use Donquixote\Cf\Util\StringUtil;
use Drupal\cfrplugin\Controller\Controller_ReportIface;
use Drupal\Core\Render\Element\RenderElement;

/**
 * @RenderElement("cfrplugin_drilldown_container")
 */
class RenderElement_DrilldownContainer extends RenderElement {

  /**
   * Returns the element properties for this element.
   *
   * @return array
   *   An array of element properties. See
   *   \Drupal\Core\Render\ElementInfoManagerInterface::getInfo() for
   *   documentation of the standard properties of all elements, and the
   *   return value format.
   */
  public function getInfo() {
    return [
      '#theme_wrappers' => ['container'],
      '#process' => ['form_process_container'],
      '#cfrplugin_interface' => NULL,
      '#cfrplugin_context' => NULL,
      /* @see _cfrplugin_pre_render_drilldown_container() */
      '#pre_render' => [[self::class, 'preRender']],
    ];
  }

  /**
   * @param array $element
   *
   * @return array
   */
  public static function preRender(array $element) {

    $interface = $element['#cfrplugin_interface'];
    $interface_label = StringUtil::interfaceGenerateLabel($interface);
    $replacements = ['@type' => $interface_label];

    $element['#attributes']['data:cfrplugin_interface'] = $interface;

    $tools = [];

    $tools['copy']['#markup']
      = '<a class="cfrplugin-copy">' . t('Copy "@type" configuration (local storage)', $replacements) . '</a>';

    $tools['paste']['#markup']
      = '<a class="cfrplugin-paste">' . t('Paste "@type" configuration (local storage)', $replacements) . '</a>';

    $tools[]['#markup'] = '<hr/>';

    # $tools[]['#markup'] = '<strong>' . check_plain($interface_label) . '</strong>';

    if (\Drupal::currentUser()->hasPermission('view cfrplugin report')) {

      $tools['inspect'] = [
        '#type' => 'link',
        // Will be replaced on client side.
        '#title' => t('About "@name" plugin'),
        '#url' => Controller_ReportIface::route($interface)->url(),
        '#attributes' => [
          'class' => ['cfrplugin-inspect'],
          'target' => '_blank',
          'title' => t('About this plugin.'),
        ],
      ];

      $tools['report'] = [
        '#type' => 'link',
        '#title' => t('About "@type" plugins', $replacements),
        '#url' => Controller_ReportIface::route($interface)->url(),
        '#attributes' => [
          'target' => '_blank',
          'title' => t('Definitions for @type plugins.', $replacements),
        ],
      ];

      $tools['demo'] = [
        '#type' => 'link',
        '#title' => t('Demo / Code generator.'),
        '#url' => Controller_ReportIface::route($interface, 'demo')->url(),
        '#attributes' => [
          'class' => ['cfrplugin-demo'],
          'target' => '_blank',
          'title' => t('Demo / Code generator.'),
        ],
      ];
    }

    $element['tools'] = [
      '#weight' => -999,
      '#type' => 'container',
      '#attributes' => [
        'class' => ['cfrplugin-tools'],
        'style' => 'display: none;',
      ],
      'top' => [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cfrplugin-tools-handle',
        ],
        '#children' => t('tools'),
      ],
      'items' => $tools + [
          '#theme_wrappers' => ['container'],
          '#attributes' => [
            'class' => ['cfrplugin-tools-dropdown'],
          ],
        ],
    ];

    return $element;
  }

}
