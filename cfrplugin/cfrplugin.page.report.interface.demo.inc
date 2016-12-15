<?php
use Donquixote\CallbackReflection\Util\CodegenUtil;
use Drupal\cfrapi\CodegenHelper\CodegenHelper;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
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

  $object = $configurator->confGetValue($settings);

  if ($object instanceof $interface) {

    $out['summary'] = [
      '#type' => 'fieldset',
      '#title' => t('Plugin summary'),
      'summary' => [
        '#markup' => $configurator->confGetSummary($settings, new SummaryBuilder_Static()),
      ],
    ];

    $out['object'] = [
      '#type' => 'fieldset',
      '#title' => t('Behavior object'),
    ];

    if (function_exists('krumong')) {
      $out['object']['dump']['#markup'] = krumong()->dump($object);
    }
    elseif (function_exists('dpm')) {
      $out['object']['dump']['#markup'] = krumo_ob($object);
      $out['object']['notice']['#markup'] = '<p>' . t('Install krumong to see private and protected member variables.') . '</p>';
    }
    else {
      $out['object']['notice']['#markup'] = t('No dump utility available. Install devel and/or krumong.');
    }
  }

  if (!$configurator instanceof ConfToPhpInterface) {
    return $out;
  }


  $out['codegen'] = [
    '#type' => 'fieldset',
    '#title' => t('Generated PHP code'),
  ];

  $php = $configurator->confGetPhp($settings, new CodegenHelper());
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
