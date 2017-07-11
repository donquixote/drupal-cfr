<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Form\D7\Util\D7FormUtil;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Util\ConfUtil;

/**
 * @Cf
 */
class PartialD7Formator_Drilldown implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   */
  public function __construct(CfSchema_DrilldownInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf);

    $_this = $this;

    $form = [
      '#type' => 'container',
      '#attributes' => ['class' => ['cfr-drilldown']],
      '#tree' => TRUE,
      'id' => D7FormUtil::optionsSchemaBuildSelectElement(
        $this->schema,
        $id,
        $label),
      '#input' => TRUE,
      '#title' => $label,
      '#default_value' => $conf = [
        'id' => $id,
        'options' => $optionsConf,
      ],
      '#process' => [
        function (array $element, array &$form_state, array &$form) use ($_this, $id, $optionsConf, $helper) {

          $element = $_this->processElement(
            $element,
            $form_state,
            $id,
            $optionsConf,
            $helper);

          $element = D7FormUtil::elementsBuildDependency(
            $element,
            $form_state,
            $form);

          return $element;
        },
      ],
      '#after_build' => [
        function (array $element, array &$form_state) use ($_this) {

          return $_this->elementAfterBuild($element, $form_state);
        },
      ],
    ];

    return $form;
  }

  /**
   * @param array $element
   * @param array $form_state
   * @param string $defaultId
   * @param mixed $defaultOptionsConf
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  private function processElement(
    array $element,
    array &$form_state,
    $defaultId,
    $defaultOptionsConf,
    D7FormatorHelperInterface $helper)
  {
    $value = $element['#value'];

    $id = isset($value['id'])
      ? $value['id']
      : NULL;

    if ($id !== $defaultId) {
      $defaultOptionsConf = NULL;
    }

    $prevId = isset($value['_previous_id'])
      ? $value['_previous_id']
      : NULL;

    if (NULL !== $prevId && $id !== $prevId && isset($form_state['input'])) {
      // Don't let values leak from one plugin to the other.
      ConfUtil::confUnsetNestedValue(
        $form_state['input'],
        array_merge($element['#parents'], ['options']));
    }

    $element['options'] = $this->idConfBuildOptionsFormWrapper(
      $id,
      $defaultOptionsConf,
      $helper);

    $element['options']['_previous_id'] = [
      '#type' => 'hidden',
      '#value' => $id,
      '#parents' => array_merge($element['#parents'], ['_previous_id']),
      '#weight' => -99,
    ];

    return $element;
  }

  /**
   * @param array $element
   * @param array $form_state
   *
   * @return array
   */
  private function elementAfterBuild(array $element, array &$form_state) {

    ConfUtil::confUnsetNestedValue(
      $form_state['input'],
      array_merge($element['#parents'], ['_previous_id']));

    ConfUtil::confUnsetNestedValue(
      $form_state['values'],
      array_merge($element['#parents'], ['_previous_id']));

    return $element;
  }

  /**
   * @param string|null $id
   * @param mixed $subConf
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  private function idConfBuildOptionsFormWrapper(
    $id,
    $subConf,
    D7FormatorHelperInterface $helper)
  {
    if (NULL === $id) {
      return [];
    }

    if (NULL === $subSchema = $this->schema->idGetSchema($id)) {
      return [];
    }

    $optionsForm = $helper->schemaConfGetD7Form(
      $subSchema, $subConf, NULL
    );

    if (empty($optionsForm)) {
      return [];
    }

    // @todo Unfortunately, #collapsible fieldsets do not play nice with Views UI.
    // See https://www.drupal.org/node/2624020
    # $options_form['#collapsed'] = TRUE;
    # $options_form['#collapsible'] = TRUE;
    return [
      '#type' => 'container',
      # '#type' => 'fieldset',
      # '#title' => $this->idGetOptionsLabel($id),
      '#attributes' => ['class' => ['cfrapi-child-options']],
      '#process' => [
        function(array $element /*, array &$form_state */) {
          if (isset($element['fieldset_content'])) {
            $element['fieldset_content']['#parents'] = $element['#parents'];
          }
          return $element;
        },
      ],
      'fieldset_content' => $optionsForm,
    ];
  }
}
