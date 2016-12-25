<?php
use Donquixote\CallbackReflection\Util\CodegenUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilder_Static;
use Drupal\cfrplugin\Util\UiUtil;

/**
 * @param string $interface
 *
 * @return array
 */
function _cfrplugin_report_interface_demo_page($interface) {

  $settings = isset($_GET['plugin'])
    ? $_GET['plugin']
    : [];


  $out = [];

  /* @see cfrplugin_report_interface_demo_page_form() */
  $out['form'] = drupal_get_form('cfrplugin_report_interface_demo_page_form', $interface, $settings);

  $configurator = cfrplugin()->interfaceGetConfigurator($interface);

  if (!$settings) {
    return $out;
  }

  $out['summary'] = [
    '#type' => 'fieldset',
    '#title' => t('Plugin summary'),
    'summary' => [
      '#markup' => $configurator->confGetSummary($settings, new SummaryBuilder_Static()),
    ],
  ];

  $out['conf'] = _cfrplugin_ui_dump_data($settings, t('Configuration data'));

  $object = $configurator->confGetValue($settings);
  $out['object'] = _cfrplugin_ui_dump_data($object, t('Behavior object'));

  $out['codegen'] = [
    '#type' => 'fieldset',
    '#title' => t('Generated PHP code'),
  ];

  $php = $configurator->confGetPhp($settings, new CfrCodegenHelper());
  $php = CodegenUtil::autoIndent($php, '  ', '    ');
  $aliases = CodegenUtil::aliasify($php);
  $aliases_php = '';
  foreach ($aliases as $class => $alias) {
    if (TRUE === $alias) {
      $aliases_php .= 'use ' . $class . ";\n";
    }
    else {
      $aliases_php .= 'use ' . $class . ' as ' . $alias . ";\n";
    }
  }

  if ('' !== $aliases_php) {
    $aliases_php = "\n" . $aliases_php;
  }

  $php = <<<EOT
<?php
$aliases_php
class C {

  /**
   * @CfrPlugin("myPlugin", "My plugin")
   *
   * @return \\$interface
   */
  public static function create() {

    return $php;
  }
}
EOT;
  $out['codegen']['help']['#markup'] = '<p>' . t("You can use the code below as a starting point for a custom plugin in a custom module.<br/>If you do so, don't forget to implement !hook, and set up a PSR-4 namespace directory structure for your class files.", ['!hook' => '<code>hook_cfrplugin_info()</code>']) . '</p>';
  $out['codegen']['code']['#markup'] = UiUtil::highlightPhp($php);

  return $out;
}

/**
 * @param mixed $data
 * @param string $label
 *
 * @return array
 */
function _cfrplugin_ui_dump_data($data, $label) {

  $element = [
    '#type' => 'fieldset',
    '#title' => $label,
  ];

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

/**
 * @param array $form
 * @param array $form_state
 * @param string $interface
 * @param array $settings
 *
 * @return array
 */
function cfrplugin_report_interface_demo_page_form(array $form, array &$form_state, $interface, $settings) {

  $form['plugin'] = [
    '#type' => 'cfrplugin',
    '#cfrplugin_interface' => $interface,
    '#title' => t('Plugin'),
    '#default_value' => $settings,
  ];

  $form['actions']['#type'] = 'actions';
  $form['actions']['submit'] = [
    '#type' => 'submit',
    '#value' => t('Show'),
  ];

  return $form;
}

/**
 * @param array $form
 * @param array $form_state
 */
function cfrplugin_report_interface_demo_page_form_submit(array &$form, array &$form_state) {
  $options['query']['plugin'] = $form_state['values']['plugin'];
  $options['query']['dpm'] = !empty($form_state['values']['dpm']);
  drupal_goto($_GET['q'], $options);
}
