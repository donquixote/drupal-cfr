<?php
use Donquixote\CallbackReflection\Util\CodegenUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\Exception\ConfToValueException;
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

  $out['conf'] = UiUtil::dumpDataInFieldset($settings, t('Configuration data'));

  try {
    $object = $configurator->confGetValue($settings);
    if ($object instanceof $interface) {
      $out['object'] = UiUtil::dumpDataInFieldset($object, t('Behavior object'));
    }
    else {
      drupal_set_message(t('The confGetValue() method had an unexpected return value.'), 'warning');
      $out['object'] = UiUtil::dumpDataInFieldset($object, t('Unexpected value or object'));
    }
  }
  catch (\Exception $e) {
    if ($e instanceof ConfToValueException) {
      drupal_set_message(t('The configuration is incomplete or invalid.'), 'warning');
    }
    else {
      drupal_set_message(t('The confGetValue() method threw an unexpected type of exception.'), 'warning');
    }
    $out['exception'] = [
        '#type' => 'fieldset',
        '#title' => t('Exception'),
        '#description' => '<p>' . t('Cfrplugin was unable to generate a behavior object for the given configuration.') . '</p>',
      ]
      + UiUtil::displayException($e);
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
  <li>Replace "myPlugin", "My plugin" and "class C" with more suitable names, and put the class into a namespace.</li>
  <li>Leave the <code>@return</code> tag in place, because it tells cfrplugindiscovery about the plugin type.</li>
  <li>Fix all <code>@todo</code> items. These occur if code generation was incomplete.</li>
</ul>
EOT
  );

  $out['codegen']['code']['#markup'] = UiUtil::highlightPhp($php);

  return $out;
}

/**
 * @param array $form
 * @param array $form_state
 * @param string $interface
 * @param array $settings
 *
 * @return array
 */
function cfrplugin_report_interface_demo_page_form(array $form,
  /** @noinspection PhpUnusedParameterInspection */ array &$form_state, $interface, $settings) {

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
function cfrplugin_report_interface_demo_page_form_submit(
  /** @noinspection PhpUnusedParameterInspection */ array &$form, array &$form_state) {
  $options['query']['plugin'] = $form_state['values']['plugin'];
  drupal_goto($_GET['q'], $options);
}
