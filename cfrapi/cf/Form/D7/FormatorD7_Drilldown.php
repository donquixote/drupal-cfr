<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\Form\D7\Util\D7FormUtil;
use Donquixote\Cf\Optionlessness\OptionlessnessInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Options\CfSchema_Options_Fixed;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\ConfUtil;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Cf
 */
class FormatorD7_Drilldown implements FormatorD7Interface, OptionableFormatorD7Interface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @var bool
   */
  private $required = TRUE;

  /**
   * @var (\Donquixote\Cf\Form\D7\FormatorD7Interface|false)[]
   */
  private $formators = [];

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(CfSchema_DrilldownInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    $this->schema = $schema;
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public function getOptionalFormator() {

    if (!$this->required) {
      return NULL;
    }

    $clone = clone $this;
    $clone->required = FALSE;
    return $clone;
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   */
  public function confGetD7Form($conf, $label) {

    list($id, $optionsConf) = ConfUtil::confGetIdOptions($conf);

    $_this = $this;

    $form = [
      '#type' => 'container',
      '#attributes' => ['class' => ['cfr-drilldown']],
      '#tree' => TRUE,
      'id' => D7FormUtil::optionsSchemaBuildSelectElement(
        $this->getOptionsSchema(),
        $id,
        $label,
        $this->required),
      '#input' => TRUE,
      '#title' => $label,
      '#default_value' => $conf = [
        'id' => $id,
        'options' => $optionsConf,
      ],
      '#process' => [
        function (array $element, FormStateInterface $form_state, array $form) use ($_this, $id, $optionsConf) {

          $element = $_this->processElement(
            $element,
            $form_state,
            $id,
            $optionsConf);

          $element = D7FormUtil::elementsBuildDependency(
            $element,
            $form_state,
            $form);

          return $element;
        },
      ],
      '#after_build' => [
        function (array $element, FormStateInterface $form_state) use ($_this) {

          return $_this->elementAfterBuild($element, $form_state);
        },
      ],
    ];

    $form['id']['#attributes']['class'][] = 'cfr-drilldown-select';

    $form['#attached']['library'][] = 'cfrapi/form';

    return $form;
  }

  /**
   * @return \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private function getOptionsSchema() {

    $groupedOptions = [];
    /** @var string[] $groupOptions */
    foreach ($this->schema->getGroupedOptions() as $groupLabel => $groupOptions) {
      foreach ($groupOptions as $id => $label) {
        $idSchema = $this->schema->idGetSchema($id);
        if (!$this->schemaIsOptionless($idSchema)) {
          $label .= '…';
        }
        $groupedOptions[$groupLabel][$id] = $label;
      }
    }

    return new CfSchema_Options_Fixed($groupedOptions);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return bool
   */
  private function schemaIsOptionless(CfSchemaInterface $schema) {

    $optionlessnessOrNull = $this->schemaToAnything->schema(
      $schema,
      OptionlessnessInterface::class);

    return 1
      && NULL !== $optionlessnessOrNull
      && $optionlessnessOrNull instanceof OptionlessnessInterface
      && $optionlessnessOrNull->isOptionless();
  }

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param string $defaultId
   * @param mixed $defaultOptionsConf
   *
   * @return array
   */
  private function processElement(
    array $element,
    FormStateInterface $form_state,
    $defaultId,
    $defaultOptionsConf)
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

    if (NULL !== $prevId && $id !== $prevId && ($input = &$form_state->getUserInput())) {
      // Don't let values leak from one plugin to the other.
      ConfUtil::confUnsetNestedValue(
        $input,
        array_merge($element['#parents'], ['options']));
    }

    $element['options'] = $this->idConfBuildOptionsFormWrapper(
      $id,
      $defaultOptionsConf);

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
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  private function elementAfterBuild(array $element, FormStateInterface $form_state) {

    ConfUtil::confUnsetNestedValue(
      $form_state->getUserInput(),
      array_merge($element['#parents'], ['_previous_id']));

    ConfUtil::confUnsetNestedValue(
      $form_state->getValues(),
      array_merge($element['#parents'], ['_previous_id']));

    return $element;
  }

  /**
   * @param string|null $id
   * @param mixed $subConf
   *
   * @return array
   */
  private function idConfBuildOptionsFormWrapper(
    $id,
    $subConf)
  {
    if (NULL === $id) {
      return [];
    }

    if (FALSE === $subFormator = $this->idGetFormatorOrFalse($id)) {
      return [];
    }

    $optionsForm = $subFormator->confGetD7Form($subConf, NULL);

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

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|false
   */
  private function idGetFormatorOrFalse($id) {
    return isset($this->formators[$id])
      ? $this->formators[$id]
      : $this->formators[$id] = $this->idBuildFormatorOrFalse($id);
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|false
   */
  private function idBuildFormatorOrFalse($id) {

    if (NULL === $schema = $this->schema->idGetSchema($id)) {
      return FALSE;
    }

    if (NULL === $formator = D7FormSTAUtil::formator(
        $schema,
        $this->schemaToAnything
      )
    ) {
      return FALSE;
    }

    return $formator;
  }
}
