<?php
use Donquixote\CallbackReflection\Util\CodegenUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilder_Static;
use Drupal\cfrplugin\Util\BacktraceUtil;
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

  try {
    $object = $configurator->confGetValue($settings);
    if ($object instanceof $interface) {
      $out['object'] = _cfrplugin_ui_dump_data($object, t('Behavior object'));
    }
    else {
      drupal_set_message(t('The confGetValue() method had an unexpected return value.'), 'warning');
      $out['object'] = _cfrplugin_ui_dump_data($object, t('Unexpected value or object'));
    }
  }
  catch (\Exception $e) {
    if ($e instanceof InvalidConfigurationException) {
      drupal_set_message(t('The configuration is incomplete or invalid.'), 'warning');
    }
    else {
      drupal_set_message(t('The confGetValue() method threw an unexpected type of exception.'), 'warning');
    }
    $out['exception'] = _cfrplugin_ui_dump_exception($e);
  }

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

  $out['codegen']['help']['#markup'] = t(
    // @todo Is it a good idea to send full HTML to t()?
    <<<EOT
<p>You can use the code below as a starting point for a custom plugin in a custom module.</p>
<p>If you do so, don't forget to:</p>
<ul>
  <li>Implement <code>hook_cfrplugin_info()</code> similar to how other modules do it.</li>
  <li>Set up a PSR-4 namespace directory structure for your class files.</li>
  <li>Leave the <code>@return</code> tag in place, because it tells cfrplugindiscovery about the plugin type.</li>
  <li>Fix all <code>@todo</code> items. These occur if code generation was not successful.</li>
</ul>
EOT
  );

  $out['codegen']['code']['#markup'] = UiUtil::highlightPhp($php);

  return $out;
}

/**
 * @param \Exception $e
 *
 * @return array
 */
function _cfrplugin_ui_dump_exception(\Exception $e) {

  $file = $e->getFile();
  $e_class = get_class($e);
  $e_class_reflection = new \ReflectionClass($e_class);

  return [
    '#type' => 'fieldset',
    '#title' => t('Exception'),
    '#description' => '<p>' . t('Cfrplugin was unable to generate a behavior object for the given configuration.') . '</p>',
    'class' => [
      '#markup' => ''
        . '<dl>'
        . '  <dt>' . t('Exception in line %line of %file', ['%line' => $e->getLine(), '%file' => basename($file)]) . '</dt>'
        . '  <dd><code>' . check_plain($file) . '</code></dd>'
        . '  <dt>' . t('Exception class: %class', ['%class' => $e_class_reflection->getShortName()]) . '</dt>'
        . '  <dd>' . check_plain($e_class) . '</dt>'
        . '  <dt>' . t('Exception message:') . '</dt>'
        . '  <dd>' . check_plain($e->getMessage()) . '</dd>'
        . '</dl>'
    ],
    'trace_label' => [
      '#markup' => '<div>' . t('Exception stack trace') . ':</div>',
    ],
    'trace' => _cfrplugin_ui_dump_data(BacktraceUtil::exceptionGetRelativeNicetrace($e)),
  ];
}

/**
 * @param mixed $data
 * @param string $fieldset_label
 *
 * @return array
 */
function _cfrplugin_ui_dump_data($data, $fieldset_label = NULL) {

  $element = [];

  if (NULL !== $fieldset_label) {
    $element = [
      '#type' => 'fieldset',
      '#title' => $fieldset_label,
    ];
  }

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
