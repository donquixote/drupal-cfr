<?php
use Drupal\cfrapi\Context\CfrContext;
use Drupal\cfrreflection\Util\StringUtil;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_element_info().
 *
 * @return array[]
 */
function cfrplugin_element_info() {

  return [
    'cfrplugin' => [
      '#input' => TRUE,
      /* @see _cfrplugin_element_process() */
      '#process' => ['_cfrplugin_element_process'],
      /* @see _cfrplugin_element_value() */
      '#value_callback' => '_cfrplugin_element_value',
      '#cfrplugin_interface' => NULL,
      '#cfrplugin_context' => NULL,
    ],
    // Drilldown form elements can exist for many purposes.
    // But the main purpose is to choose the id and options for a cfr plugin.
    // While other drilldowns use a regular '#type' => 'container', the
    // drilldowns for cfrplugin use '#type' => '#cfrplugin_drilldown_container'.
    // For such containers we want to be able to add additional links and tools.
    'cfrplugin_drilldown_container' => [
      '#theme_wrappers' => ['container'],
      /* @see form_process_container() */
      '#process' => ['form_process_container'],
      '#cfrplugin_interface' => NULL,
      '#cfrplugin_context' => NULL,
      /* @see _cfrplugin_pre_render_drilldown_container() */
      '#pre_render' => ['_cfrplugin_pre_render_drilldown_container'],
    ],
  ];
}

/**
 * @param array $element
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 * @param array $form
 *
 * @return array
 */
function _cfrplugin_element_process(array $element,
  /** @noinspection PhpUnusedParameterInspection */ FormStateInterface $form_state,
  /** @noinspection PhpUnusedParameterInspection */ array &$form) {

  $cfrContext = $element['#cfrplugin_context'];
  if (is_array($cfrContext)) {
    $cfrContext = new CfrContext($cfrContext);
  }

  $configurator = empty($element['#required'])
    ? cfrplugin()->interfaceGetOptionalConfigurator($element['#cfrplugin_interface'], $cfrContext)
    : cfrplugin()->interfaceGetConfigurator($element['#cfrplugin_interface'], $cfrContext);

  # kdpm($configurator, __FUNCTION__);

  $element['cfrplugin'] = $configurator->confGetForm($element['#default_value'], $element['#title']);

  $element['cfrplugin']['#parents'] = $element['#parents'];

  return $element;
}

/**
 * @param array $element
 * @param mixed|false $input
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *
 * @return mixed ;
 */
function _cfrplugin_element_value(array $element, $input = FALSE,
  /** @noinspection PhpUnusedParameterInspection */ FormStateInterface $form_state) {

  if (FALSE !== $input) {
    return $input;
  }

  if (isset($element['#default_value'])) {
    return $element['#default_value'];
  }

  return [];
}

/**
 * Callback for '#pre_render'.
 *
 * @param array $element
 *
 * @return array
 */
function _cfrplugin_pre_render_drilldown_container(array $element) {

  $interface = $element['#cfrplugin_interface'];
  $interface_label = StringUtil::interfaceGenerateLabel($interface);
  $replacements = ['@type' => $interface_label];
  $interface_slug = _cfrplugin_interface_slug($interface);

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
      '#markup' => l(
        t('About "@name" plugin'),
        "admin/reports/cfrplugin/$interface_slug",
        ['attributes' => [
          'class' => ['cfrplugin-inspect'],
          'target' => '_blank',
          'title' => t('About this plugin.'),
        ]]),
    ];

    $tools['report'] = [
      '#markup' => l(
        t('About "@type" plugins', $replacements),
        "admin/reports/cfrplugin/$interface_slug",
        ['attributes' => [
          'target' => '_blank',
          'title' => t('Definitions for @type plugins.', $replacements),
        ]]),
    ];

    $tools['demo'] = [
      '#markup' => l(
        t('Demo / Code generator.'),
        "admin/reports/cfrplugin/$interface_slug/demo",
        ['attributes' => [
          'class' => ['cfrplugin-demo'],
          'target' => '_blank',
          'title' => t('Demo / Code generator.'),
        ]]),
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

  $element['#attached']['css'][] = drupal_get_path('module', 'cfrplugin') . '/css/cfrplugin.drilldown-tools.css';
  $element['#attached']['js'][] = drupal_get_path('module', 'cfrplugin') . '/js/cfrplugin.drilldown-tools.js';

  return $element;
}
